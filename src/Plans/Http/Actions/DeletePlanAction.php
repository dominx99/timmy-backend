<?php declare(strict_types=1);

namespace App\Plans\Http\Actions;

use App\Plans\Application\Delete\DeletePlanCommand;
use App\Shared\Contracts\CommandBusContract;
use App\Shared\Http\Responses\SuccessResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class DeletePlanAction
{
    private CommandBusContract $commandBus;

    public function __construct(CommandBusContract $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $this->commandBus->handle(new DeletePlanCommand($args["planId"]));

        return SuccessResponse::create();
    }
}
