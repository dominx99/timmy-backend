<?php declare(strict_types = 1);

use App\Accounts\Application\Find\FindUserByEmailQuery;
use App\Accounts\Application\Find\FindUserByEmailQueryHandler;
use App\Measurements\Application\Find\FindMeasurementsByPlanQuery;
use App\Measurements\Application\Find\FindMeasurementsByPlanQueryHandler;
use App\Plans\Application\Exist\PlanExistsInPeriodForTimeMeterQuery;
use App\Plans\Application\Find\FindPlansByDateQuery;
use App\Plans\Application\Find\FindPlansByDateQueryHandler;
use App\TimeMeters\Application\Find\FindAllTimeMetersQuery;
use App\TimeMeters\Application\Find\FindAllTimeMetersQueryHandler;
use App\Plans\Application\Exist\PlanExistsInPeriodForTimeMeterQueryHandler;
use App\Plans\Application\Find\FindPlansByPeriodQuery;
use App\Plans\Application\Find\IsPlanStartedQuery;
use App\Plans\Application\Find\IsPlanStartedQueryHandler;
use App\Shared\Application\Calendar\GetActualTimeQuery;
use App\Shared\Application\Calendar\GetActualTimeQueryHandler;
use App\Plans\Application\Find\FindPlansByPeriodQueryHandler;
use App\Plans\Application\Find\IsPlanOutdatedQuery;
use App\Plans\Application\Find\IsPlanOutdatedQueryHandler;

$container->set(
    FindUserByEmailQuery::class,
    DI\autowire(FindUserByEmailQueryHandler::class),
);

$container->set(
    FindAllTimeMetersQuery::class,
    DI\autowire(FindAllTimeMetersQueryHandler::class),
);

$container->set(
    FindPlansByDateQuery::class,
    DI\autowire(FindPlansByDateQueryHandler::class),
);

$container->set(
    PlanExistsInPeriodForTimeMeterQuery::class,
    DI\autowire(PlanExistsInPeriodForTimeMeterQueryHandler::class),
);

$container->set(
    IsPlanStartedQuery::class,
    DI\autowire(IsPlanStartedQueryHandler::class),
);

$container->set(
    FindMeasurementsByPlanQuery::class,
    DI\autowire(FindMeasurementsByPlanQueryHandler::class),
);

$container->set(
    GetActualTimeQuery::class,
    DI\autowire(GetActualTimeQueryHandler::class),
);

$container->set(
    FindPlansByPeriodQuery::class,
    DI\autowire(FindPlansByPeriodQueryHandler::class),
);

$container->set(
    IsPlanOutdatedQuery::class,
    DI\autowire(IsPlanOutdatedQueryHandler::class),
);
