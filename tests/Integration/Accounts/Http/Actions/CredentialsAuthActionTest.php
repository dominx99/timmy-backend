<?php declare(strict_types=1);

namespace Tests\Integration\Accounts\Http\Actions;

use Tests\BaseTestCase;
use Tests\DatabaseMigrations;
use Tests\DatabaseSeeds;

final class CredentialsAuthActionTest extends BaseTestCase
{
    use DatabaseMigrations, DatabaseSeeds;

    /** @test */
    public function that_authenticates_user()
    {
        $request = $this->createRequest("POST", "v1/auth/login", [
            "grant_type" => "credentials",
            "email"      => "example@test.com",
            "password"   => "secret",
        ]);

        $response = $this->app->handle($request);
        $body = json_decode((string) $response->getBody(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertContains("status", array_keys($body));
        $this->assertSame("success", $body["status"]);
    }

    // TODO: WRITE TEST FOR WRONG GRANT TYPE
}
