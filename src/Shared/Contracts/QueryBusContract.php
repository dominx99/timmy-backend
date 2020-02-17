<?php declare(strict_types=1);

namespace App\Shared\Contracts;

interface QueryBusContract
{
    public function handle(Query $command);
}
