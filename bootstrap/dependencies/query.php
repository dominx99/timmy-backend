<?php declare(strict_types = 1);

use App\Accounts\Application\Find\FindUserByEmailQuery;
use App\Accounts\Application\Find\FindUserByEmailQueryHandler;

$container->set(
    FindUserByEmailQuery::class,
    DI\autowire(FindUserByEmailQueryHandler::class),
);
