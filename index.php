<?php

use App\Shared\Http\Middleware\CORSMiddleware;
use Slim\Factory\AppFactory;
use DI\Container;
use Dotenv\Dotenv;
use App\Shared\Http\Middleware\ExceptionMiddleware;
use App\Shared\Http\Middleware\JsonBodyParserMiddleware;
use Psr\Log\LoggerInterface;

require_once 'vendor/autoload.php';

if (getenv("APP_ENV") !== "production") {
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

$container = new Container();

require_once './bootstrap/dependencies.php';

AppFactory::setContainer($container);
$app = AppFactory::create();

/* $app->addBodyParsingMiddleware(); */

$app->addMiddleware(new ExceptionMiddleware($container->get(LoggerInterface::class)));
$app->addMiddleware(new CORSMiddleware());
$app->addRoutingMiddleware();
$app->addMiddleware(new JsonBodyParserMiddleware());
$app->addErrorMiddleware(true, false, false);

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

require_once './routes/api-v1.php';

$app->run();
