<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\JWT;

use App\Accounts\Application\DecodedToken\DecodedToken;
use App\Shared\Exceptions\BusinessException;
use Firebase\JWT\JWT;

final class JWTDecoder extends JWT
{
    public static function fromBearer(string $token): DecodedToken
    {
        if (! $token = substr($token, 7)) {
            throw new BusinessException("Not valid token");
        }

        return new DecodedToken(
            (array) static::decode($token, getenv("JWT_KEY"), ["HS256"])
        );
    }
}
