<?php declare(strict_types=1);

namespace App\Accounts\Dto;

use App\Accounts\Domain\AuthenticationException;

final class AuthResult
{
    private string $accessToken;

    public function fill(array $data): void
    {
        if (! isset($data["accessToken"])) {
            throw new AuthenticationException("No access token set.");
        }

        $this->accessToken = $data["accessToken"];
    }

    public function toArray(): array
    {
        return [
            'access_token' => $this->accessToken,
        ];
    }
}
