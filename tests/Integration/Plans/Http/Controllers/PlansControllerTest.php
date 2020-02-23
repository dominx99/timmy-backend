<?php declare(strict_types=1);

namespace Tests\Integration\TimeMeters\Http\Controllers;

use App\TimeMeters\Application\Create\CreateTimeMeterCommand;
use Tests\BaseTestCase;
use Tests\DatabaseMigrations;
use Tests\DatabaseSeeds;
use Ramsey\Uuid\Uuid;

final class PlansControllerTest extends BaseTestCase
{
    use DatabaseMigrations, DatabaseSeeds;

    /** @test */
    public function that_creates_plan()
    {
        $this->actingAs("example@test.com");

        $timeMeterId = (string) Uuid::uuid4();

        $this->commandBus->handle(new CreateTimeMeterCommand($timeMeterId, $this->userId, "Work"));

        $request = $this->createRequest("POST", "v1/plans", [
            "timeMeterId" => $timeMeterId,
            "startDate"   => "2020-02-18 00:00:00",
            "endDate"     => "2020-02-19 00:00:00",
            "minTime"     => 3000,
            "maxTime"     => 4800,
        ]);

        $response = $this->app->handle($request);
        $body = json_decode((string) $response->getBody(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->arrayHasKey("status", $body);
        $this->assertSame("success", $body["status"]);
    }
}
