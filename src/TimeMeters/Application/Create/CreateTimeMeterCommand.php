<?php declare(strict_types=1);

namespace App\TimeMeters\Application\Create;

use App\Shared\Contracts\Command;

final class CreateTimeMeterCommand implements Command
{
    public string $timeMeterId;
    public string $userId;
    public string $name;

    public function __construct(string $timeMeterId, string $userId, string $name)
    {
        $this->timeMeterId = $timeMeterId;
        $this->userId = $userId;
        $this->name = $name;
    }
}
