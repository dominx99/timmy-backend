<?php declare(strict_types=1);

namespace App\Shared\Http\Actions;

use App\Shared\Contracts\Calendar\CalendarContract;
use App\Shared\Http\Responses\SuccessResponse;
use Psr\Http\Message\ResponseInterface;

final class GetActualTimeAction
{
    private CalendarContract $calendar;

    public function __construct(CalendarContract $calendar)
    {
        $this->calendar = $calendar;
    }

    public function __invoke(): ResponseInterface
    {
        return SuccessResponse::create(
            $this->calendar->now()->format("Y-m-d H:i:s"),
        );
    }
}
