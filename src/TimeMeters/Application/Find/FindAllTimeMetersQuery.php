<?php declare(strict_types=1);

namespace App\TimeMeters\Application\Find;

use App\Shared\Contracts\Query;

final class FindAllTimeMetersQuery implements Query
{
    private string $userId;

    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }

    public function userId(): string
    {
        return $this->userId;
    }
}
