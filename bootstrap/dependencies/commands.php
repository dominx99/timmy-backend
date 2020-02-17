<?php declare(strict_types = 1);

use App\Accounts\Application\Auth\SetAccessTokenOnUserCommandHandler;
use App\Accounts\Application\Auth\VerifyPasswordCommand;
use App\Accounts\Application\Auth\VerifyPasswordCommandHandler;
use App\TimeMeters\Application\Create\CreateTimeMeterCommand;
use App\TimeMeters\Application\Create\CreateTimeMeterCommandHandler;
use App\Accounts\Application\Auth\SetAccessTokenOnUserCommand;
use App\Accounts\Application\Create\CreateUserCommand;
use App\Accounts\Application\Create\CreateUserCommandHandler;

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
