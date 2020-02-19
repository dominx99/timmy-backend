<?php declare(strict_types=1);

namespace Tests\Integration\TimeMeters\Http\Controllers;

use App\TimeMeters\Application\Create\CreateTimeMeterCommand;
use Doctrine\DBAL\Connection;
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
            "time_meter_id" => $timeMeterId,
            "start_date"    => "2020-02-18 00:00:00",
            "end_date"      => "2020-02-19 00:00:00",
            "min_time"      => 3000,
            "max_time"      => 4800,
        ]);

        $response = $this->app->handle($request);
        $body = json_decode((string) $response->getBody(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->arrayHasKey("status", $body);
        $this->assertSame("success", $body["status"]);
    }
}
