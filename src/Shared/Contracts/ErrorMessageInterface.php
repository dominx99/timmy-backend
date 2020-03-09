<?php declare(strict_types=1);

namespace App\Shared\Contracts;

interface ErrorMessageInterface
{
    const PLAN_IN_EXISTING_PERIOD = "Cannot create plan in existing period.";
    const PLAN_ALREADY_STARTED = "Plan already started.";
}
