<?php declare(strict_types=1);

namespace App\Plans\Domain;

use App\TimeMeters\Domain\TimeMeterView;

final class PlanView
{
    private string $id;
    private string $userId;
    private string $timeMeterId;
    private string $startDate;
    private string $endDate;
    private int $minTime;
    private int $maxTime;
    private TimeMeterView $timeMeter;

    private function __construct(
        string $id,
        string $userId,
        string $timeMeterId,
        string $startDate,
        string $endDate,
        int $minTime,
        int $maxTime
    ) {
        $this->id          = $id;
        $this->userId      = $userId;
        $this->timeMeterId = $timeMeterId;
        $this->startDate   = $startDate;
        $this->endDate     = $endDate;
        $this->minTime     = $minTime;
        $this->maxTime     = $maxTime;
    }

    public static function create(array $data): self
    {
        return new static(
            $data["id"],
            $data["user_id"],
            $data["time_meter_id"],
            $data["start_date"],
            $data["end_date"],
            (int) $data["min_time"],
            (int) $data["max_time"],
        );
    }

    public function setTimeMeter(TimeMeterView $timeMeter): void
    {
        $this->timeMeter = $timeMeter;
    }

    public function id(): string
    {
        return $this->id;
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

    public function minTime(): int
    {
        return $this->minTime;
    }

    public function maxTime(): int
    {
        return $this->maxTime;
    }

    public function toArray(): array
    {
        return [
            "id"          => $this->id(),
            "userId"      => $this->userId(),
            "timeMeterId" => $this->timeMeterId(),
            "startDate"   => $this->startDate(),
            "endDate"     => $this->endDate(),
            "minTime"     => $this->minTime(),
            "maxTime"     => $this->maxTime(),
            "timeMeter"   => $this->timeMeter->toArray(),
        ];
    }
}
