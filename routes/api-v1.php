<?php declare(strict_types = 1);

use App\Accounts\Http\Actions\AuthAction;
use App\Accounts\Http\Actions\RegisterAction;
use App\Accounts\Http\Middleware\JWTAuthMiddleware;
use App\Measurements\Http\Actions\StartMeasurementAction;
use App\Measurements\Http\Actions\StopMeasurementAction;
use App\Plans\Http\Actions\FindPlansByDateAction;
use App\TimeMeters\Http\Controllers\TimeMetersController;
use Slim\Interfaces\RouteCollectorProxyInterface;
use App\Plans\Http\Controllers\PlansController;
use App\Shared\Http\Actions\GetActualTimeAction;

$app->group("/v1", function (RouteCollectorProxyInterface $group) {
    $group->post("/auth/login", AuthAction::class);
    $group->post("/auth/register", RegisterAction::class);

    $group->group("/", function (RouteCollectorProxyInterface $group) {
        $group->get("time-meters", TimeMetersController::class . ':index');
        $group->post("time-meters", TimeMetersController::class . ':store');
        $group->post("plans", PlansController::class . ':store');
        $group->get("plans/by/date", FindPlansByDateAction::class);

        $group->post("measurements/{planId}/start", StartMeasurementAction::class);
        $group->post("measurements/{planId}/stop", StopMeasurementAction::class);

        $group->get("time/now", GetActualTimeAction::class);
    })->addmiddleware(new JWTAuthMiddleware());
});
