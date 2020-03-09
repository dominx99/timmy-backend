<?php declare(strict_types=1);

namespace App\Measurements\Application\Find;

use App\Shared\Contracts\Query;

final class FindMeasurementsByPlanQuery implements Query
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
