<?php declare(strict_types=1);

namespace Tests\Integration\TimeMeters\Http\Controllers;

use App\TimeMeters\Application\Create\CreateTimeMeterCommand;
use Tests\BaseTestCase;
use Tests\DatabaseMigrations;
use Tests\DatabaseSeeds;
use Ramsey\Uuid\Uuid;

final class TimeMetersControllerTest extends BaseTestCase
{
    use DatabaseMigrations, DatabaseSeeds;

    /** @test */
    public function that_successfuly_creates_time_meter()
    {
        $this->actingAs("example@test.com");

        $request = $this->createRequest("POST", "v1/time-meters", [
            "name" => "Work",
        ]);

        $response = $this->app->handle($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(["status" => "success"], json_decode((string) $response->getBody(), true));
    }

    /** @test */
    public function that_returns_response_based_on_validation_exception()
    {
        $this->actingAs("example@test.com");

        $request = $this->createRequest("POST", "v1/time-meters");

        $response = $this->app->handle($request);

        $expectedResult = [
            "errors" => [
                "name" => [
                    "Name must not be empty",
                ],
            ],
        ];

        $this->assertSame(422, $response->getStatusCode());
        $this->assertSame($expectedResult, json_decode((string) $response->getBody(), true));
    }

    /** @test */
    public function that_returns_valid_time_meters()
    {
        $this->actingAs("example@test.com");

        $timeMeters = [
            [
                "id"     => (string) Uuid::uuid4(),
                "userId" => $this->userId,
                "name"   => "Work",
            ],
            [
                "id"     => (string) Uuid::uuid4(),
                "userId" => $this->userId,
                "name"   => "School",
            ],
        ];

        foreach($timeMeters as $timeMeter) {
            $this->commandBus->handle(new CreateTimeMeterCommand(
                $timeMeter["id"],
                $timeMeter["userId"],
                $timeMeter["name"],
            ));
        }

        $request = $this->createRequest("GET", "v1/time-meters");

        $response = $this->app->handle($request);
        $body = json_decode((string) $response->getBody(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertArrayHasKey("data", $body);
        $this->assertCount(2, $body["data"]);
    }
}
