<?php declare(strict_types=1);

namespace App\Measurements\Http\Actions;

use App\Measurements\Application\Find\FindMeasurementsByPlanQuery;
use App\Measurements\Application\Update\StopMeasurementCommand;
use App\Plans\Application\Find\IsPlanStartedQuery;
use App\Shared\Contracts\CommandBusContract;
use App\Shared\Contracts\QueryBusContract;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Shared\Http\Responses\JsonResponse;
use App\Shared\Contracts\ErrorMessageInterface;
use Fig\Http\Message\StatusCodeInterface;
use App\Measurements\Domain\MeasurementView;
use App\Shared\Http\Responses\SuccessResponse;

final class StopMeasurementAction
{
    private CommandBusContract $commandBus;
    private QueryBusContract $queryBus;

    public function __construct(CommandBusContract $commandBus, QueryBusContract $queryBus)
    {
        $this->commandBus = $commandBus;
        $this->queryBus   = $queryBus;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ) {
        if (! $this->queryBus->handle(new IsPlanStartedQuery($args["planId"]))) {
            return JsonResponse::create([
                'error' => ErrorMessageInterface::PLAN_IS_NOT_STARTED,
            ], StatusCodeInterface::STATUS_BAD_REQUEST);
        }

        $measurements = $this->queryBus->handle(new FindMeasurementsByPlanQuery($args["planId"]));

        $measurement = $measurements->find(
            fn(MeasurementView $measurement) => $measurement->status() === "started"
        );

        $this->commandBus->handle(new StopMeasurementCommand($measurement->id()));

        return SuccessResponse::create();
    }
}
