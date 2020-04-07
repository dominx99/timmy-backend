<?php declare(strict_types=1);

namespace Tests\Integration\Plans\Http\Actions;

use App\Plans\Application\Create\CreatePlanCommand;
use Fig\Http\Message\StatusCodeInterface;
use Ramsey\Uuid\Uuid;
use Tests\BaseTestCase;
use Tests\DatabaseMigrations;
use Tests\DatabaseSeeds;

final class DeletePlanActionTest extends BaseTestCase
{
    use DatabaseMigrations, DatabaseSeeds;

    /** @test */
    public function that_deletes_plan()
    {
        $this->actingAs("example@test.com");

        $planId = (string) Uuid::uuid4();

        $this->commandBus->handle(new CreatePlanCommand(
            $planId,
            (string) Uuid::uuid4(),
            $this->userId,
            "2020-02-02 20:02:20",
            "2020-02-02 20:02:21",
        ));

        $this->assertDatabaseHas("plans", ["id" => $planId]);

        $response = $this->app->handle($this->createRequest("DELETE", "v1/plans/${planId}"));

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());

        $this->assertDatabaseMissing("plans", ["id" => $planId]);
    }
}
