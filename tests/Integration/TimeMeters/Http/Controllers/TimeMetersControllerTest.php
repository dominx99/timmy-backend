<?php declare(strict_types=1);

namespace Tests\Integration\TimeMeters\Http\Controllers;

use Tests\BaseTestCase;
use Tests\DatabaseMigrations;
use Tests\DatabaseSeeds;

final class TimeMetersControllerTest extends BaseTestCase
{
    use DatabaseMigrations, DatabaseSeeds;

    /** @test */
    public function that_returns_validation_errors()
    {
        $this->actingAs("example@test.com");

        $request = $this->createRequest("POST", "v1/time-meters", [
            "name" => "Work",
        ]);

        $response = $this->app->handle($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(["status" => "success"], json_decode((string) $response->getBody(), true));
    }
}
