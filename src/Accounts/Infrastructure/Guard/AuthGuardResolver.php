<?php declare(strict_types=1);

namespace App\Accounts\Infrastructure\Guard;

use App\Accounts\Domain\AuthenticationException;
use App\Accounts\Contracts\AuthGuard;
use App\Accounts\Contracts\AuthGuardResolverContract;
use Psr\Container\ContainerInterface;

final class AuthGuardResolver implements AuthGuardResolverContract
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    const AVAILABLE_GRANT_TYPES = [
        'credentials' => CredentialsGuard::class,
    ];

    public function resolve(string $grantType): AuthGuard
    {
        if (! in_array($grantType, array_keys(self::AVAILABLE_GRANT_TYPES))) {
            throw new AuthenticationException("Not allowed grant type.");
        }

        return $this->container->get(self::AVAILABLE_GRANT_TYPES[$grantType]);
    }
}
