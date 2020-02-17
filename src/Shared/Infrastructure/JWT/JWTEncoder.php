<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\JWT;

use Firebase\JWT\JWT;
use App\Accounts\Application\Views\UserView;

final class JWTEncoder extends JWT
{
    public static function fromUser(UserView $user): string
    {
        return static::encode([
            'id' => $user->id(),
        ], getenv('JWT_KEY'));
    }

    public static function fromArray(array $user): string
    {
        return static::encode([
            'id' => $user["id"],
        ], getenv('JWT_KEY'));
    }
}
