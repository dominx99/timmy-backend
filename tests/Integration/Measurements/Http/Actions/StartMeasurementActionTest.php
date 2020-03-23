<?php declare(strict_types=1);

namespace Tests\Integration\Measurements\Http\Actions;

use App\Plans\Application\Create\CreatePlanCommand;
use App\Shared\Contracts\Calendar\CalendarContract;
use App\Shared\Contracts\ErrorMessageInterface;
use DateInterval;
use DateTime;
use Fig\Http\Message\StatusCodeInterface;
use Ramsey\Uuid\Uuid;
use Tests\BaseTestCase;
use Tests\DatabaseMigrations;
use Tests\DatabaseSeeds;

final class StartMeasurementActionTest extends BaseTestCase
{
    use DatabaseMigrations, DatabaseSeeds;

    /** @test */
    public function that_creates_new_measurement()
    {
        $planId = (string) Uuid::uuid4();

        $this->actingAs("example@test.com");

        $response = $this->app->handle($this->createRequest(
            "POST",
            "v1/measurements/{$planId}/start",
        ));

        $this->assertEquals(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
    }

    /** @test */
    public function that_returns_exception_when_want_to_start_plan_twice()
    {
        $planId = (string) Uuid::uuid4();

        $this->actingAs("example@test.com");

        $response = $this->app->handle($this->createRequest(
            "POST",
            "v1/measurements/{$planId}/start",
        ));

        $this->assertEquals(StatusCodeInterface::STATUS_OK, $response->getStatusCode());

        $response = $this->app->handle($this->createRequest(
            "POST",
            "v1/measurements/{$planId}/start",
        ));

        $this->assertEquals(StatusCodeInterface::STATUS_BAD_REQUEST, $response->getStatusCode());
    }

    /** @test */
    public function that_returns_exception_when_want_to_start_outdated_plan()
    {
        $planId = (string) Uuid::uuid4();

        $this->actingAs("example@test.com");

        $now = new DateTime("now");
        $calendar = $this->createMock(CalendarContract::class);
        $calendar->method("now")->willReturn($now);

        $this->commandBus->handle(new CreatePlanCommand(
            $planId,
            (string) Uuid::uuid4(),
            $this->userId,
            "2020-02-02 14:14:14",
            $now->sub(new DateInterval("P10D"))->format("Y-m-d H:i:s"),
        ));

        $response = $this->app->handle($this->createRequest(
            "POST",
            "v1/measurements/{$planId}/start",
        ));

        $this->assertEquals(StatusCodeInterface::STATUS_BAD_REQUEST, $response->getStatusCode());

        $body = json_decode((string) $response->getBody(), true);

        $this->assertArrayHasKey("error", $body);
        $this->assertSame(ErrorMessageInterface::PLAN_IS_OUTDATED, $body["error"]);
    }
}
