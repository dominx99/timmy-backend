<?php declare(strict_types=1);

namespace Tests\Integration\TimeMeters\Http\Controllers;

use App\Plans\Application\Create\CreatePlanCommand;
use App\TimeMeters\Application\Create\CreateTimeMeterCommand;
use Tests\BaseTestCase;
use Tests\DatabaseSeeds;
use Tests\DatabaseMigrations;
use Ramsey\Uuid\Uuid;

final class FindPlansByDateActionTest extends BaseTestCase
{
    use DatabaseMigrations, DatabaseSeeds;

    private string $timeMeterId;

    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs("example@test.com");
        $this->timeMeterId = (string) Uuid::uuid4();

        $this->commandBus->handle(new CreateTimeMeterCommand($this->timeMeterId, $this->userId, "Work"));
    }

    /**
        @test
        @dataProvider dates
     */
    public function that_creates_plan(array $dates, string $date, int $expectedCount)
    {
        foreach ($dates as $period) {
            $this->commandBus->handle(new CreatePlanCommand(
                (string) Uuid::uuid4(),
                $this->timeMeterId,
                $this->userId,
                $period[0],
                $period[1],
            ));
        }

        $request = $this->createRequest("GET", "v1/plans/by/date?date={$date}");

        $response = $this->app->handle($request);
        $body = json_decode((string) $response->getBody(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->arrayHasKey("status", $body);
        $this->assertSame("success", $body["status"]);
        $this->assertCount($expectedCount, $body["data"]);
    }

    public function dates()
    {
        return [
            [
                [["2020-02-01 00:00:00", "2020-02-08 23:59:59",],
                ["2020-02-03 00:00:00", "2020-02-03 23:59:59",]],
                "2020-02-02 15:41:13", 1
            ],
            [
                [["2020-02-05 00:00:00", "2020-02-12 23:59:59",],
                ["2020-02-01 00:00:00", "2020-02-01 23:59:59",],
                ["2020-02-01 00:00:00", "2020-02-08 23:59:59",]],
                "2020-02-01 15:41:13", 2
            ],
        ];
    }
}
