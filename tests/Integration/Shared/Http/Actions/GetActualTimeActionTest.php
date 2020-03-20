<?php declare(strict_types=1);

namespace Tests\Integration\Shared\Http\Actions;

use App\Shared\Contracts\Calendar\CalendarContract;
use Tests\BaseTestCase;
use \DateTime;
use Tests\DatabaseMigrations;
use Tests\DatabaseSeeds;

final class GetActualActionTest extends BaseTestCase
{
    use DatabaseMigrations, DatabaseSeeds;

    /** @test */
    public function that_returns_valid_date()
    {
        $this->actingAs("example@test.com");

        $expectedNow = new DateTime("now");

        $calendar = $this->createMock(CalendarContract::class);
        $calendar->method("now")->willReturn($expectedNow);

        $this->container->set(CalendarContract::class, $calendar);

        $response = $this->app->handle($this->createRequest("GET", "v1/time/now"));

        $body = json_decode((string) $response->getBody(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertArrayHasKey("data", $body);
        $this->assertSame($expectedNow->format("Y-m-d H:i:s"), $body["data"]);
    }
}
