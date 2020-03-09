<?php declare(strict_types=1);

namespace Tests\Integration\Measurements\Http\Actions;

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
}
