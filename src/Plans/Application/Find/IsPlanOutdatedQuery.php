<?php declare(strict_types=1);

namespace App\Plans\Application\Find;

use App\Shared\Contracts\Query;

final class IsPlanOutdatedQuery implements Query
{
    private string $planId;

    public function __construct(string $planId)
    {
        $this->planId = $planId;
    }

    public function planId(): string
    {
        return $this->planId;
    }
}
