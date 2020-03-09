<?php declare(strict_types=1);

namespace App\Measurements\Application\Create;

use App\Shared\Contracts\Command;

final class CreateMeasurementForPlanCommand implements Command
{
    private string $measurementId;
    private string $planId;

    public function __construct(string $measurementId, string $plandId)
    {
        $this->measurementId = $measurementId;
        $this->planId        = $plandId;
    }

    public function measurementId(): string
    {
        return $this->measurementId;
    }

    public function planId(): string
    {
        return $this->planId;
    }
}
