<?php declare(strict_types=1);

namespace App\Plans\Application\Find;

use App\Shared\Contracts\Query;

final class FindPlansByDateQuery implements Query
{
    private string $userId;
    private string $date;

    public function __construct(string $userId, string $date)
    {
        $this->userId = $userId;
        $this->date = $date;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function date(): string
    {
        return $this->date;
    }
}
