<?php declare(strict_types = 1);

use App\Accounts\Application\Auth\SetAccessTokenOnUserCommandHandler;
use App\Accounts\Application\Auth\VerifyPasswordCommand;
use App\Accounts\Application\Auth\VerifyPasswordCommandHandler;
use App\TimeMeters\Application\Create\CreateTimeMeterCommand;
use App\TimeMeters\Application\Create\CreateTimeMeterCommandHandler;
use App\Accounts\Application\Auth\SetAccessTokenOnUserCommand;
use App\Accounts\Application\Create\CreateUserCommand;
use App\Accounts\Application\Create\CreateUserCommandHandler;
use App\Measurements\Application\Create\CreateMeasurementForPlanCommandHandler;
use App\Measurements\Application\Create\CreateMeasurementForPlanCommand;
use App\Measurements\Application\Update\StopMeasurementCommand;
use App\Measurements\Application\Update\StopMeasurementCommandHandler;
use App\Plans\Application\Create\CreatePlanCommand;
use App\Plans\Application\Create\CreatePlanCommandHandler;
use App\Plans\Application\Delete\DeletePlanCommand;
use App\Plans\Application\Delete\DeletePlanCommandHandler;

$container->set(
    CreateTimeMeterCommand::class,
    DI\autowire(CreateTimeMeterCommandHandler::class),
);

$container->set(
    VerifyPasswordCommand::class,
    DI\autowire(VerifyPasswordCommandHandler::class),
);

$container->set(
    SetAccessTokenOnUserCommand::class,
    DI\autowire(SetAccessTokenOnUserCommandHandler::class),
);

$container->set(
    CreateUserCommand::class,
    DI\autowire(CreateUserCommandHandler::class),
);

$container->set(
    CreatePlanCommand::class,
    DI\autowire(CreatePlanCommandHandler::class),
);

$container->set(
    CreateMeasurementForPlanCommand::class,
    DI\autowire(CreateMeasurementForPlanCommandHandler::class),
);

$container->set(
    StopMeasurementCommand::class,
    DI\autowire(StopMeasurementCommandHandler::class),
);

$container->set(
    DeletePlanCommand::class,
    DI\autowire(DeletePlanCommandHandler::class),
);
