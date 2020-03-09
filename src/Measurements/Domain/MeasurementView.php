<?php declare(strict_types=1);

namespace App\Measurements\Domain;

final class MeasurementView
{
    private string $id;
    private string $planId;
    private string $status;
    private ?string $stoppedAt;
    private string $createdAt;

    private function __construct(
        string $id,
        string $planId,
        string $status,
        ?string $stoppedAt = null,
        string $createdAt
    ) {
        $this->id         = $id;
        $this->planId     = $planId;
        $this->status     = $status;
        $this->stoppedAt = $stoppedAt;
        $this->createdAt = $createdAt;
    }

    public static function create(array $data): self
    {
        return new static(
            $data["id"],
            $data["plan_id"],
            $data["status"],
            $data["stopped_at"] ?? null,
            $data["created_at"],
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function planId(): string
    {
        return $this->planId;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function stoppedAt(): ?string
    {
        return $this->stoppedAt;
    }

    public function createdAt(): string
    {
        return $this->createdAt;
    }

    public function toArray(): array
    {
        return [
            "id"         => $this->id(),
            "planId"     => $this->planId(),
            "status"     => $this->status(),
            "stopped_at" => $this->stoppedAt(),
            "created_at" => $this->createdAt(),
        ];
    }
}
