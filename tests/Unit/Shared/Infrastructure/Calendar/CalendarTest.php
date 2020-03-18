<?php declare(strict_types=1);

namespace Tests\Unit\Shared\Infrastructure\Calendar;

use Tests\BaseTestCase;
use App\Shared\Infrastructure\Calendar\Calendar;

final class CalendarTest extends BaseTestCase
{
    /** @test */
    public function that_now_function_works()
    {
        $now = new \Datetime("now");

        $calendar = new Calendar();

        $this->assertSame($now->getTimestamp(), $calendar->now()->getTimestamp());
    }
}
