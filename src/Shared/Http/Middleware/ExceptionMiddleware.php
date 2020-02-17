<?php declare(strict_types=1);

namespace App\Shared\Http\Middleware;

use App\Accounts\Domain\UserNotFoundException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use App\Shared\Exceptions\BusinessException;
use App\Shared\Http\Responses\JsonResponse;
use Fig\Http\Message\StatusCodeInterface;
use App\Shared\Exceptions\ValidationException;

final class ExceptionMiddleware implements MiddlewareInterface
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $response = $handler->handle($request);
        } catch (BusinessException $e) {
            return JsonResponse::create([
                'error' => $e->getMessage(),
            ], $e->getCode ? $e->getCode() : StatusCodeInterface::STATUS_BAD_REQUEST);
        } catch (ValidationException $e) {
            return JsonResponse::create([
                'errors' => $e->messages(),
            ], $e->getCode ? $e->getCode() : StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY);
        }

        return $response;
    }
}
