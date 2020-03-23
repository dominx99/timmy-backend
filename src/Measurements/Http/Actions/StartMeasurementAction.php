<?php declare(strict_types=1);

namespace App\Measurements\Http\Actions;

use App\Measurements\Application\Create\CreateMeasurementForPlanCommand;
use App\Plans\Application\Find\IsPlanOutdatedQuery;
use App\Plans\Application\Find\IsPlanStartedQuery;
use App\Shared\Contracts\CommandBusContract;
use App\Shared\Contracts\ErrorMessageInterface;
use App\Shared\Contracts\QueryBusContract;
use App\Shared\Http\Responses\JsonResponse;
use App\Shared\Http\Responses\SuccessResponse;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;

final class StartMeasurementAction
{
    private QueryBusContract $queryBus;
    private CommandBusContract $commandBus;

    public function __construct(QueryBusContract $queryBus, CommandBusContract $commandBus)
    {
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        if ($this->queryBus->handle(new IsPlanStartedQuery($args["planId"]))) {
            return JsonResponse::create([
                'error' => ErrorMessageInterface::PLAN_ALREADY_STARTED,
            ], StatusCodeInterface::STATUS_BAD_REQUEST);
        }

        if ($this->queryBus->handle(new IsPlanOutdatedQuery($args["planId"]))) {
            return JsonResponse::create([
                'error' => ErrorMessageInterface::PLAN_IS_OUTDATED,
            ], StatusCodeInterface::STATUS_BAD_REQUEST);
        }

        $this->commandBus->handle(new CreateMeasurementForPlanCommand(
            (string) Uuid::uuid4(),
            $args["planId"]
        ));

        return SuccessResponse::create();
    }
}
