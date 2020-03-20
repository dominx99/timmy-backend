<?php declare(strict_types=1);

namespace App\Measurements\Http\Actions;

use App\Measurements\Application\Find\FindMeasurementsByPlanQuery;
use App\Measurements\Domain\MeasurementView;
use App\Shared\Contracts\QueryBusContract;
use App\Shared\Http\Responses\SuccessResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class FindMeasurementsByPlanAction
{
    private QueryBusContract $queryBus;

    public function __construct(QueryBusContract $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        /** @var \App\Measurements\Domain\Measurements */
        $measurements = $this->queryBus->handle(new FindMeasurementsByPlanQuery($args["planId"]));

        return SuccessResponse::create(
            $measurements->map(fn (MeasurementView $measurement) => $measurement->toArray())->toArray()
        );
    }
}
