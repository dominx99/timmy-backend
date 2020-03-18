<?php declare(strict_types=1);

namespace Tests\Integration\Measurements\Http\Actions;

use App\Measurements\Application\Create\CreateMeasurementForPlanCommand;
use App\Plans\Application\Create\CreatePlanCommand;
use Tests\BaseTestCase;
use Tests\DatabaseMigrations;
use Tests\DatabaseSeeds;
use Ramsey\Uuid\Uuid;
use Fig\Http\Message\StatusCodeInterface;

final class StopMeasurementActionTest extends BaseTestCase
{
    use DatabaseMigrations, DatabaseSeeds;

    private string $planId;

    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs("example@test.com");
        $this->planId = (string) Uuid::uuid4();

        $this->commandBus->handle(new CreatePlanCommand(
            $this->planId,
            (string) Uuid::uuid4(),
            $this->userId,
            "2019-09-08",
            "2019-10-20"
        ));

        $this->commandBus->handle(new CreateMeasurementForPlanCommand(
            (string) Uuid::uuid4(),
            $this->planId,
        ));

    }

    /** @test */
    public function that_stops_measurement()
    {
        $response = $this->app->handle($this->createRequest(
            "POST",
            "v1/measurements/{$this->planId}/stop",
        ));

        $this->assertEquals(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
    }

    /** @test */
    public function that_returns_exception_when_want_to_stop_stopped_measurement()
    {
        $response = $this->app->handle($this->createRequest(
            "POST",
            "v1/measurements/{$this->planId}/stop",
        ));

        $this->assertEquals(StatusCodeInterface::STATUS_OK, $response->getStatusCode());

        $response = $this->app->handle($this->createRequest(
            "POST",
            "v1/measurements/{$this->planId}/stop",
        ));

        $this->assertEquals(StatusCodeInterface::STATUS_BAD_REQUEST, $response->getStatusCode());
    }
}
