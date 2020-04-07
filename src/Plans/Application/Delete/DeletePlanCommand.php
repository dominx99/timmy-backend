<?php declare(strict_types=1);

namespace App\Plans\Application\Delete;

use App\Shared\Contracts\Command;

final class DeletePlanCommand implements Command
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
