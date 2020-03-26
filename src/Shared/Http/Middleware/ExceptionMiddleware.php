<?php declare(strict_types=1);

namespace App\Shared\Http\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use App\Shared\Exceptions\BusinessException;
use Fig\Http\Message\StatusCodeInterface;
use App\Shared\Exceptions\ValidationException;
use Slim\Psr7\Response;

final class ExceptionMiddleware implements MiddlewareInterface
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = new Response();
        $response = $response->withHeader('Content-Type', 'application/json');

        try {
            $response = $handler->handle($request);
        } catch (BusinessException $e) {
            $response->getBody()->write(json_encode(["error" => $e->getMessage()]));
            $response = $response->withStatus(
                $e->getCode() ? $e->getCode() : StatusCodeInterface::STATUS_BAD_REQUEST
            );
        } catch (ValidationException $e) {
            $response->getBody()->write(json_encode(["errors" => $e->messages()]));
            $response = $response->withStatus(
                $e->getCode() ? $e->getCode() : StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY
            );
        }

        return $response;
    }
}
