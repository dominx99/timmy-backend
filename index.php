<?php

use Slim\Factory\AppFactory;
use DI\Container;
use Dotenv\Dotenv;
use App\Shared\Http\Middleware\ExceptionMiddleware;
use App\Shared\Http\Middleware\JsonBodyParserMiddleware;
use Slim\Psr7\Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

require_once 'vendor/autoload.php';

if (getenv("APP_ENV") !== "production") {
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

$container = new Container();

require_once './bootstrap/dependencies.php';

AppFactory::setContainer($container);
$app = AppFactory::create();

$app->addBodyParsingMiddleware();

$app->add(function (Request $request, RequestHandlerInterface $handler): Response {
    $routeContext = RouteContext::fromRequest($request);
    $routingResults = $routeContext->getRoutingResults();
    $methods = $routingResults->getAllowedMethods();
    $requestHeaders = $request->getHeaderLine('Access-Control-Request-Headers');

    $response = $handler->handle($request);

    $response = $response->withHeader('Access-Control-Allow-Origin', '*');
    $response = $response->withHeader('Access-Control-Allow-Methods', implode(',', $methods));
    $response = $response->withHeader('Access-Control-Allow-Headers', $requestHeaders);

    $response = $response->withHeader('Access-Control-Allow-Credentials', 'true');

    return $response;
});

$app->addRoutingMiddleware();
$app->addMiddleware(new ExceptionMiddleware());
$app->addMiddleware(new JsonBodyParserMiddleware());
$app->addErrorMiddleware(true, false, false);

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

require_once './routes/api-v1.php';

$app->run();
