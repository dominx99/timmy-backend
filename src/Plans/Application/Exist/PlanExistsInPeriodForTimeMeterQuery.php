<?php declare(strict_types=1);

namespace App\Plans\Application\Exist;

use App\Shared\Contracts\Query;

final class PlanExistsInPeriodForTimeMeterQuery implements Query
{
    private string $userId;
    private string $timeMeterId;
    private string $startDate;
    private string $endDate;

    public function __construct(
        string $userId,
        string $timeMeterId,
        string $startDate,
        string $endDate
    ) {
        $this->userId = $userId;
        $this->timeMeterId = $timeMeterId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function timeMeterId(): string
    {
        return $this->timeMeterId;
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
