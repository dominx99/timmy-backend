<?php declare(strict_types=1);

namespace Tests\Integration\TimeMeters\Http\Controllers;

use App\Shared\Contracts\ErrorMessageInterface;
use App\TimeMeters\Application\Create\CreateTimeMeterCommand;
use Fig\Http\Message\StatusCodeInterface;
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

    /** @test */
    public function that_unable_to_create_plan_for_time_meter_in_same_period()
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

        $request = $this->createRequest("POST", "v1/plans", [
            "timeMeterId" => $timeMeterId,
            "startDate"   => "2020-02-06 00:00:00",
            "endDate"     => "2020-02-20 23:59:59",
            "minTime"     => 3000,
            "maxTime"     => 4800,
        ]);

        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        $this->assertSame(
            ErrorMessageInterface::PLAN_IN_EXISTING_PERIOD,
            json_decode((string) $response->getBody(), true)["error"],
        );
    }
}
