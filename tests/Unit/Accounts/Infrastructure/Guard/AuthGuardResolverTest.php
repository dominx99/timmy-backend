<?php declare(strict_types=1);

namespace Tests\Unit\Accounts\Infrastructure\Guard;

use App\Accounts\Contracts\AuthGuard;
use Tests\BaseTestCase;
use App\Accounts\Infrastructure\Guard\AuthGuardResolver;
use App\Accounts\Infrastructure\Guard\CredentialsGuard;
use App\Accounts\Contracts\AuthGuardResolverContract;
use App\Accounts\Domain\AuthenticationException;

final class AuthGuardResolverTest extends BaseTestCase
{
    private AuthGuardResolver $resolver;

    public function setUp(): void
    {
        parent::setUp();

        $this->resolver = $this->container->get(AuthGuardResolverContract::class);
    }

    /** @test */
    public function that_resolves_correctly()
    {
        $guard = $this->resolver->resolve("credentials");

        $this->assertInstanceOf(AuthGuard::class, $guard);
        $this->assertInstanceOf(CredentialsGuard::class, $guard);
    }

    /** @test */
    public function that_throws_authentication_exception_on_not_valid_guard()
    {
        $this->expectException(AuthenticationException::class);

        $this->resolver->resolve("foo");
    }
}
