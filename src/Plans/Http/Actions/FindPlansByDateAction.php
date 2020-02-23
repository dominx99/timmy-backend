<?php declare(strict_types=1);

namespace App\Plans\Http\Actions;

use App\Plans\Application\Find\FindPlansByDateQuery;
use App\Shared\Contracts\QueryBusContract;
use App\Shared\Contracts\Validation\ValidatorContract;
use App\Shared\Http\Responses\SuccessResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Plans\Application\Find\FindPlansByDateRules;

final class FindPlansByDateAction
{
    private QueryBusContract $queryBus;
    private ValidatorContract $validator;

    public function __construct(QueryBusContract $queryBus, ValidatorContract $validator)
    {
        $this->queryBus = $queryBus;
        $this->validator = $validator;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getQueryParams();

        $this->validator->validate($body, new FindPlansByDateRules());

        $plans = $this->queryBus->handle(new FindPlansByDateQuery(
            $request->getAttribute("decodedToken")->id(),
            $body["date"],
        ));

        return SuccessResponse::create($plans->map(fn ($plan) => $plan->toArray())->toArray());
    }
}
