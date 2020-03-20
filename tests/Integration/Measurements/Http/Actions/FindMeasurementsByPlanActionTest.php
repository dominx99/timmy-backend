<?php declare(strict_types=1);

namespace Tests\Integration\Measurements\Http\Actions;

use App\Measurements\Application\Create\CreateMeasurementForPlanCommand;
use Fig\Http\Message\StatusCodeInterface;
use Ramsey\Uuid\Uuid;
use Tests\BaseTestCase;
use Tests\DatabaseMigrations;
use Tests\DatabaseSeeds;

final class FindMeasurementsByPlanActionTest extends BaseTestCase
{
    use DatabaseMigrations, DatabaseSeeds;

    /** @test */
    public function that_fetches_valid_measurements()
    {
        $this->actingAs("example@test.com");
        $planId = (string) Uuid::uuid4();

        $this->commandBus->handle(new CreateMeasurementForPlanCommand(
            (string) Uuid::uuid4(),
            $planId,
        ));

        $this->commandBus->handle(new CreateMeasurementForPlanCommand(
            (string) Uuid::uuid4(),
            $planId,
        ));

        $this->assertMeasurementsResponse($planId, 2);

        $this->commandBus->handle(new CreateMeasurementForPlanCommand(
            (string) Uuid::uuid4(),
            $planId,
        ));

        $this->assertMeasurementsResponse($planId, 3);
    }

    private function assertMeasurementsResponse(string $planId, int $expectedCount)
    {
        $response = $this->app->handle($this->createRequest("GET", "v1/plans/{$planId}/measurements"));

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());

        $body = json_decode((string) $response->getBody(), true);

        $this->assertArrayHasKey("data", $body);
        $this->assertCount($expectedCount, $body["data"]);
    }
}
