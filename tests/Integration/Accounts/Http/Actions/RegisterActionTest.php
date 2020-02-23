<?php declare(strict_types=1);

namespace Tests\Integration\Accounts\Http\Actions;

use Tests\BaseTestCase;
use Tests\DatabaseMigrations;

final class RegisterActionTest extends BaseTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function that_authenticates_user()
    {
        $request = $this->createRequest("POST", "v1/auth/register", [
            "email"      => "example@test.com",
            "password"   => "secret",
        ]);

        $response = $this->app->handle($request);

        $body = json_decode((string) $response->getBody(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertContains("status", array_keys($body));
        $this->assertSame("success", $body["status"]);

        $this->assertDatabaseHas("users", [
            "email" => "example@test.com",
        ]);
    }

    /** @test */
    public function that_cannot_create_user_with_same_email()
    {
        $request = $this->createRequest("POST", "v1/auth/register", [
            "email"      => "example@test.com",
            "password"   => "secret",
        ]);

        $response = $this->app->handle($request);

        $this->assertDatabaseHas("users", [
            "email" => "example@test.com",
        ]);

        $body = json_decode((string) $response->getBody(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertContains("status", array_keys($body));
        $this->assertSame("success", $body["status"]);

        $response = $this->app->handle($request);

        $this->assertSame(422, $response->getStatusCode());
        $this->assertSame([
            "errors" => [
                "email" => [
                    "Email is already taken.",
                ],
            ],
        ], json_decode((string) $response->getBody(), true));
    }
}
