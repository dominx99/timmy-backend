<?php declare(strict_types=1);

namespace Tests\Integration\Shared\Http\Middleware;

use App\Shared\Exceptions\BusinessException;
use App\Shared\Exceptions\ValidationException;
use Fig\Http\Message\StatusCodeInterface;
use Tests\BaseTestCase;

final class ExceptionMiddlewareTest extends BaseTestCase
{
    /** @test */
    public function that_catches_business_exception()
    {
        $this->app->get("/business", function () {
            throw new BusinessException("Something went wrong");
        });

        $response = $this->app->handle(
            $this->createRequest("GET", "/business"),
        );

        $this->assertSame(400, $response->getStatusCode());

        $body = json_decode((string) $response->getBody(), true);

        $this->assertSame("Something went wrong", $body["error"]);
    }

    /** @test */
    public function that_catches_validation_exception()
    {
        $errors = [
            "name" => [
                "Must not be empty",
                "Must start with capitalized",
            ],
            "age" => [
                "Must be number",
            ],
        ];

        $this->app->get("/validation", function () use ($errors) {
            throw ValidationException::withMessages($errors);
        });

        $response = $this->app->handle(
            $this->createRequest("GET", "/validation"),
        );

        $this->assertSame(StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY, $response->getStatusCode());

        $body = json_decode((string) $response->getBody(), true);

        $this->assertSame($errors, $body["errors"]);
    }
}
