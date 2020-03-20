<?php declare(strict_types=1);

namespace Tests\Unit\Shared\Application\Calendar;

use App\Shared\Contracts\Calendar\CalendarContract;
use Tests\BaseTestCase;
use \DateTime;
use App\Shared\Application\Calendar\GetActualTimeQuery;

final class GetActualTimeTest extends BaseTestCase
{
    /** @test */
    public function that_returns_valid_date()
    {
        $expectedNow = new DateTime("now");

        $calendar = $this->createMock(CalendarContract::class);
        $calendar->method("now")->willReturn($expectedNow);

        $this->container->set(CalendarContract::class, $calendar);

        $now = $this->queryBus->handle(new GetActualTimeQuery());

        $this->assertSame($expectedNow->getTimestamp(), $now->getTimestamp());
    }
}
