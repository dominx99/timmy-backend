<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Contracts\Command;
use App\Shared\Contracts\CommandHandler;
use App\Shared\Exceptions\SystemException;
use DI\Container;
use DI\Definition\Exception\InvalidDefinition;
use App\Shared\Contracts\CommandBusContract;
use Psr\Log\LoggerInterface;

final class CommandBus implements CommandBusContract
{
    private Container $container;
    private LoggerInterface $logger;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->logger    = $container->get(LoggerInterface::class);
    }

    public function handle(Command $command): void
    {
        try {
            /** @var CommandHandler */
            $handler = $this->resolve(get_class($command));

            $handler->handle($command);
        } catch (\Throwable $t) {
            throw $t;
        }
    }

    private function resolve(string $command): CommandHandler
    {
        try {
            return $this->container->get($command);
        } catch (InvalidDefinition $e) {
            throw new SystemException("Command handler not found.");
        }
    }
}
