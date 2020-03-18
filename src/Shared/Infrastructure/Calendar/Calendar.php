<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\Calendar;

use \Datetime;
use App\Shared\Contracts\Calendar\CalendarContract;

final class Calendar implements CalendarContract
{
    public function now(): DateTime
    {
        return new DateTime("now");
    }
}
