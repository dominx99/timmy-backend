<?php declare(strict_types=1);

namespace App\Measurements\Application\Update;

use App\Shared\Contracts\Command;

final class StopMeasurementCommand implements Command
{
    private string $measurementId;

    public function __construct(string $measurementId)
    {
        $this->measurementId = $measurementId;
    }

    public function measurementId(): string
    {
        return $this->measurementId;
    }
}
