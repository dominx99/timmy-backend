<?php declare(strict_types=1);

namespace Tests\Unit\Shared\Infrastructure\JWT;

use App\Accounts\Application\Views\UserView;
use App\Shared\Infrastructure\JWT\JWTEncoder;
use Firebase\JWT\JWT;
use Ramsey\Uuid\Uuid;
use Tests\BaseTestCase;

final class JWTEncoderTest extends BaseTestCase
{
    /** @test */
    public function that_encodes_user_to_jwt_string_token()
    {
        $user = UserView::create([
            "id"           => (string) Uuid::uuid4(),
            "email"        => "example@test.com",
            "password"     => password_hash("secret", PASSWORD_BCRYPT),
            "access_token" => "some-token",
        ]);

        $expectedToken = JWT::encode(["id" => $user->id()], getenv("JWT_KEY"));
        $token = JWTEncoder::fromUser($user);

        $this->assertSame($expectedToken, $token);
    }

    /** @test */
    public function that_encodes_array_into_jwt_token_string()
    {
        $userId = (string) Uuid::uuid4();

        $user = [
            "id" => $userId,
        ];

        $expectedToken = JWT::encode(["id" => $userId], getenv("JWT_KEY"));
        $token = JWTEncoder::fromArray($user);

        $this->assertSame($expectedToken, $token);
    }
}
