<?php declare(strict_types=1);

namespace App\TimeMeters\Domain;

final class TimeMeterView
{
    private string $id;
    private string $userId;
    private string $name;

    private function __construct(string $id, string $userId, string $name)
    {
        $this->id     = $id;
        $this->userId = $userId;
        $this->name   = $name;
    }

    public static function create(array $data): self
    {
        return new static(
            $data["id"],
            $data["user_id"],
            $data["name"],
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function name(): string
    {
        return $this->name;
    }
}
