<?php declare(strict_types=1);

namespace App\Plans\Application\Create;

use App\Shared\Contracts\Command;

final class CreatePlanCommand implements Command
{
    private string $planId;
    private string $timeMeterId;
    private string $userId;
    private string $startDate;
    private string $endDate;
    private ?int $minTime;
    private ?int $maxTime;

    public function __construct(
        string $planId,
        string $timeMeterId,
        string $userId,
        string $startDate,
        string $endDate,
        ?int $minTime = null,
        ?int $maxTime = null
    ) {
        $this->planId = $planId;
        $this->timeMeterId = $timeMeterId;
        $this->userId = $userId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->minTime = $minTime;
        $this->maxTime = $maxTime;
    }

    public function planId(): string
    {
        return $this->planId;
    }

    public function timeMeterId(): string
    {
        return $this->timeMeterId;
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

    public function minTime(): ?int
    {
        return $this->minTime;
    }

    public function maxTime(): ?int
    {
        return $this->maxTime;
    }
}
