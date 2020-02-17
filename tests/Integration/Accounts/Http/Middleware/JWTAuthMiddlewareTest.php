<?php declare(strict_types=1);

namespace Tests\Integration\Accounts\Http\Middleware;

use Tests\BaseTestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Firebase\JWT\JWT;
use Ramsey\Uuid\Uuid;
use App\Accounts\Http\Middleware\JWTAuthMiddleware;

final class JWTAuthMiddlewareTest extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->app->get('/token', function (ServerRequestInterface $request, ResponseInterface $response) {
            $response->getBody()->write('authorized');

            return $response;
        })->addMiddleware(new JWTAuthMiddleware());
    }

    /** @test */
    public function can_access_with_token()
    {
        $token = JWT::encode(['id' => (string) Uuid::uuid4()], getenv('JWT_KEY'));

        $request = $this->createRequest("GET", "/token");
        $request = $request->withHeader('Authorization', "Bearer {$token}");

        $response = $this->app->handle($request);

        $this->assertEquals('authorized', (string) $response->getBody());
    }

    /** @test */
    public function that_invalid_token_signature_will_catch_error()
    {
        $token = JWT::encode(['id' => (string) Uuid::uuid4()], "wrong_key");

        $request = $this->createRequest("GET", "/token");
        $request = $request->withHeader('Authorization', "Bearer {$token}");

        $response = $this->app->handle($request);

        $this->assertEquals(400, $response->getStatusCode());

        $body = json_decode((string) $response->getBody(), true);

        $this->assertEquals('Signature verification failed', $body['error']);
    }

    /** @test */
    public function that_expired_token_will_return_exception()
    {
        $token = JWT::encode([
            'id'  => (string) Uuid::uuid4(),
            'exp' => time() - 3600,
        ], getenv('JWT_KEY'));

        $request = $this->createRequest("GET", "/token");
        $request = $request->withHeader('Authorization', "Bearer {$token}");

        $response = $this->app->handle($request);

        $this->assertEquals(400, $response->getStatusCode());

        $body = json_decode((string) $response->getBody(), true);

        $this->assertEquals('Expired token', $body['error']);
    }

    /** @test */
    public function that_before_valid_token_will_return_exception()
    {
        $token = JWT::encode([
            'id'  => (string) Uuid::uuid4(),
            'nbf' => time() + 3600,
        ], getenv('JWT_KEY'));

        $request = $this->createRequest("GET", "/token");
        $request = $request->withHeader('Authorization', "Bearer {$token}");

        $response = $this->app->handle($request);

        $this->assertEquals(400, $response->getStatusCode());

        $body = json_decode((string) $response->getBody(), true);

        $this->assertStringContainsString('Cannot handle token prior to', $body['error']);
    }

    /** @test */
    public function that_empty_token_will_return_error()
    {
        $request = $this->createRequest("GET", "/token");
        $request = $request->withHeader('Authorization', "");

        $response = $this->app->handle($request);

        $this->assertEquals(400, $response->getStatusCode());

        $body = json_decode((string) $response->getBody(), true);

        $this->assertEquals("Not valid token", $body['error']);
    }

    /** @test */
    public function that_not_providing_token_will_return_error()
    {
        $request = $this->createRequest("GET", "/token");

        $response = $this->app->handle($request);

        $this->assertEquals(400, $response->getStatusCode());

        $body = json_decode((string) $response->getBody(), true);

        $this->assertEquals("Token is required", $body['error']);
    }
}
