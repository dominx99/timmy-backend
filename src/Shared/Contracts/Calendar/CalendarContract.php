<?php declare(strict_types=1);

namespace App\Shared\Contracts\Calendar;

use \DateTime;

interface CalendarContract
{
    public function now(): DateTime;
}
