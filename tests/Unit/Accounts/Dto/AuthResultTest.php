<?php declare(strict_types=1);

namespace Tests\Unit\Accounts\Dto;

use App\Accounts\Domain\AuthenticationException;
use App\Accounts\Dto\AuthResult;
use Tests\BaseTestCase;

final class AuthResultTest extends BaseTestCase
{
    /** @test */
    public function that_sets_data_correctly()
    {
        $result = new AuthResult();

        $expectedArray = [
            "access_token" => "a-token",
        ];

        $result->fill([
            "accessToken" => "a-token",
            "foo"         => "bar",
        ]);

        $this->assertSame($expectedArray, $result->toArray());
    }

    /** @test */
    public function that_throws_authentication_exception_on_not_valid_data()
    {
        $this->expectException(AuthenticationException::class);

        $result = new AuthResult();

        $result->fill([
            "token" => "a-token",
        ]);
    }
}
