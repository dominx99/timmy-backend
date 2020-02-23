<?php declare(strict_types = 1);

use App\Accounts\Application\Find\FindUserByEmailQuery;
use App\Accounts\Application\Find\FindUserByEmailQueryHandler;
use App\Plans\Application\Exist\PlanExistsInPeriodForTimeMeterQuery;
use App\Plans\Application\Find\FindPlansByDateQuery;
use App\Plans\Application\Find\FindPlansByDateQueryHandler;
use App\TimeMeters\Application\Find\FindAllTimeMetersQuery;
use App\TimeMeters\Application\Find\FindAllTimeMetersQueryHandler;
use App\Plans\Application\Exist\PlanExistsInPeriodForTimeMeterQueryHandler;

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
