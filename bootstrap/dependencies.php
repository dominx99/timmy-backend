<?php declare(strict_types = 1);

use App\Accounts\Contracts\AuthGuardResolverContract;
use App\Accounts\Infrastructure\Guard\AuthGuardResolver;
use App\Shared\Application\Validation\Validator;
use App\Shared\Contracts\CommandBusContract;
use App\Shared\Contracts\QueryBusContract;
use App\Shared\Contracts\Validation\ValidatorContract;
use App\Shared\Infrastructure\Bus\CommandBus;
use App\Shared\Infrastructure\Bus\QueryBus;
use Respect\Validation\Validator as v;

$container->set(ValidatorContract::class, DI\autowire(Validator::class));
$container->set(CommandBusContract::class, DI\autowire(CommandBus::class));
$container->set(QueryBusContract::class, DI\autowire(QueryBus::class));
$container->set(AuthGuardResolverContract::class, DI\autowire(AuthGuardResolver::class));

require 'dependencies/database.php';
require 'dependencies/commands.php';
require 'dependencies/queries.php';

v::with('App\Accounts\Application\Validation\\Rules\\');
