<?php declare(strict_types=1);

namespace Tests;

use App\Shared\Infrastructure\Bus\CommandBus;
use Slim\Factory\AppFactory;
use DI\Container;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\Psr7\Factory\UriFactory;
use App\Shared\Infrastructure\JWT\JWTEncoder;
use App\Shared\Infrastructure\Bus\QueryBus;
use App\Accounts\Application\Find\FindUserByEmailQuery;
use App\Shared\Http\Middleware\ExceptionMiddleware;
use App\Shared\Http\Middleware\JsonBodyParserMiddleware;
use Doctrine\DBAL\Connection;

class BaseTestCase extends TestCase
{
    protected App $app;
    protected Container $container;
    protected CommandBus $commandBus;
    protected QueryBus $queryBus;
    protected Connection $connection;
    protected string $jwtToken;
    protected string $userId;

    public function setUp(): void
    {
        $this->createApplication();

        $uses = class_uses(static::class);

        if (in_array(DatabaseMigrations::class, $uses)) {
            $this->rollback();
            $this->migrate();
        }

        if (in_array(DatabaseSeeds::class, $uses)) {
            $this->seed();
        }
    }

    public function tearDown(): void
    {
        if (in_array(DatabaseMigrations::class, class_uses(static::class))) {
            $this->rollback();
        }

        $this->clearToken();
    }

    private function clearToken(): void
    {
        $this->jwtToken = "";
    }

    private function createApplication(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . "/../");
        $dotenv->load();

        $container = new Container();

        AppFactory::setContainer($container);

        $app = AppFactory::create();
        $app->addRoutingMiddleware();
        $app->addMiddleware(new ExceptionMiddleware());
        $app->addMiddleware(new JsonBodyParserMiddleware());
        $app->addErrorMiddleware(true, false, false);
        $container = $app->getContainer();

        require 'bootstrap/dependencies.php';
        require 'routes/api-v1.php';

        $this->app        = $app;
        $this->container  = $container;
        $this->commandBus = new CommandBus($this->container);
        $this->queryBus = new QueryBus($this->container);
        $this->connection = $this->container->get(Connection::class);
    }

    protected function actingAs(string $email): void
    {
        $user = $this->queryBus->handle(new FindUserByEmailQuery($email));

        $this->jwtToken = $this->container->get(JWTEncoder::class)->fromUser($user);
        $this->userId   = $user->id();
    }

    protected function createRequest(
        string $method,
        string $uri,
        array $params = [],
        array $headers = []
    ): ServerRequestInterface {
        $headers = array_merge([
            'Content-Type' => 'application/json',
        ], $headers);

        $request = ServerRequestCreatorFactory::create();
        $request = $request->createServerRequestFromGlobals();

        $request = $request->withMethod($method);
        $request = $request->withUri((new UriFactory())->createUri($uri));

        $request = $request->withParsedBody($params);

        foreach ($headers as $key => $header) {
            $request = $request->withHeader($key, $header);
        }

        if (! empty($this->jwtToken)) {
            $request = $request->withHeader('Authorization', "Bearer {$this->jwtToken}");
        }

        return $request;
    }
}
