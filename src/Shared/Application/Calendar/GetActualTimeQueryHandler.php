<?php declare(strict_types=1);

namespace App\Shared\Application\Calendar;

use App\Shared\Contracts\Calendar\CalendarContract;
use App\Shared\Contracts\QueryHandler;
use \DateTime;

final class GetActualTimeQueryHandler implements QueryHandler
{
    private CalendarContract $calendar;

    public function __construct(CalendarContract $calendar)
    {
        $this->calendar = $calendar;
    }

    public function handle(): DateTime
    {
        return $this->calendar->now();
    }
}
