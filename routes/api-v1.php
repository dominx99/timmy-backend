<?php declare(strict_types = 1);

use App\Accounts\Http\Actions\AuthAction;
use App\Accounts\Http\Actions\RegisterAction;
use App\Accounts\Http\Middleware\JWTAuthMiddleware;
use App\TimeMeters\Http\Controllers\TimeMetersController;
use Slim\Interfaces\RouteCollectorProxyInterface;

$app->group("/v1", function (RouteCollectorProxyInterface $group) {
    $group->post("/auth/login", AuthAction::class);
    $group->post("/auth/register", RegisterAction::class);

    $group->group("/", function (RouteCollectorProxyInterface $group) {
        $group->post("time-meters", TimeMetersController::class . ':store');
    })->addmiddleware(new JWTAuthMiddleware());
});
