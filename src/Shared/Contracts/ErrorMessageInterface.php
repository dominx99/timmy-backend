<?php declare(strict_types=1);

namespace App\Shared\Contracts;

interface ErrorMessageInterface
{
    const PLAN_IN_EXISTING_PERIOD = "Cannot create plan in existing period.";
    const PLAN_ALREADY_STARTED = "Plan already started.";
    const PLAN_IS_NOT_STARTED = "Plan is not started.";
    const PLAN_IS_OUTDATED = "Plan is outdated.";

    const MEASUREMENTS_NOT_FOUND = "Could not fetch measurements.";
}
