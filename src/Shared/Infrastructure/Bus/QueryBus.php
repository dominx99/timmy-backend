<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Contracts\QueryHandler;
use App\Shared\Exceptions\SystemException;
use DI\Container;
use DI\Definition\Exception\InvalidDefinition;
use App\Shared\Contracts\Query;
use App\Shared\Contracts\QueryBusContract;
use Psr\Log\LoggerInterface;

final class QueryBus implements QueryBusContract
{
    private Container $container;
    private LoggerInterface $logger;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->logger    = $container->get(LoggerInterface::class);
    }

    public function handle(Query $query)
    {
        try {
            /** @var QueryHandler */
            $handler = $this->resolve(get_class($query));

            return $handler->handle($query);
        } catch (\Throwable $t) {
            throw $t;
        }
    }

    private function resolve(string $query): QueryHandler
    {
        try {
            return $this->container->get($query);
        } catch (InvalidDefinition $e) {
            throw new SystemException("Query handler not found.");
        }
    }
}
