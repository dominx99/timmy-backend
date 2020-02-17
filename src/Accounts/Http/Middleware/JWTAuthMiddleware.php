<?php declare(strict_types=1);

namespace App\Accounts\Http\Middleware;

use App\Shared\Exceptions\BusinessException;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Shared\Infrastructure\JWT\JWTDecoder;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\BeforeValidException;
use Doctrine\Instantiator\Exception\UnexpectedValueException;
use Psr\Http\Message\ResponseInterface;

final class JWTAuthMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (empty($request->getHeader('Authorization'))) {
            throw new BusinessException("Token is required");
        }

        try {
            $decodedToken = JWTDecoder::fromBearer($request->getHeader('Authorization')[0]);

            $request = $request->withAttribute('decodedToken', $decodedToken);
        } catch (SignatureInvalidException | ExpiredException | BeforeValidException | UnexpectedValueException $e) {
            throw new BusinessException($e->getMessage());
        }

        $response = $handler->handle($request);

        return $response;
    }
}
