<?php declare(strict_types=1);

namespace App\Plans\Application\Find;

use App\Shared\Contracts\Query;

final class FindPlansByPeriodQuery implements Query
{
    private string $userId;
    private string $startDate;
    private string $endDate;

    public function __construct(
        string $userId,
        string $startDate,
        string $endDate
    ) {
        $this->userId = $userId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function startDate(): string
    {
        return $this->startDate;
    }

    public function endDate(): string
    {
        return $this->endDate;
    }
}
