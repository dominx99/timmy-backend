<?php declare(strict_types=1);

namespace App\Accounts\Http\Actions;

use App\Accounts\Application\Auth\AuthRules;
use App\Accounts\Contracts\AuthGuardResolverContract;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Accounts\Dto\AuthResult;
use App\Shared\Contracts\Validation\ValidatorContract;
use App\Shared\Http\Responses\SuccessResponse;

final class AuthAction
{
    private AuthGuardResolverContract $guardResolver;
    private ValidatorContract $validator;

    public function __construct(AuthGuardResolverContract $guardResolver, ValidatorContract $validator)
    {
        $this->guardResolver = $guardResolver;
        $this->validator     = $validator;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();

        $this->validator->validate($body, new AuthRules());

        $result = new AuthResult();

        $guard = $this->guardResolver->resolve($body["grant_type"]);
        $guard->authenticate($body, $result);

        return SuccessResponse::create($result->toArray());
    }
}
