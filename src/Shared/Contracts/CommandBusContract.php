<?php declare(strict_types=1);

namespace App\Shared\Contracts;

interface CommandBusContract
{
    public function handle(Command $command): void;
}
