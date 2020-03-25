<?php

use Slim\Factory\AppFactory;
use DI\Container;
use Dotenv\Dotenv;
use App\Shared\Http\Middleware\ExceptionMiddleware;
use App\Shared\Http\Middleware\JsonBodyParserMiddleware;

require_once 'vendor/autoload.php';

if (getenv("APP_ENV") !== "production") {
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

$container = new Container();

require_once './bootstrap/dependencies.php';

AppFactory::setContainer($container);
$app = AppFactory::create();

header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type");

$app->addRoutingMiddleware();
$app->addMiddleware(new ExceptionMiddleware());
$app->addMiddleware(new JsonBodyParserMiddleware());
$app->addErrorMiddleware(true, false, false);

require_once './routes/api-v1.php';

$app->run();
