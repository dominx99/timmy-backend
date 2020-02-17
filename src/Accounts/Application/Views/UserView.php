<?php declare(strict_types=1);

namespace App\Accounts\Application\Views;

final class UserView
{
    private string $id;
    private string $email;
    private string $password;
    private ?string $accessToken;

    private function __construct(string $id, string $email, string $password, ?string $accessToken)
    {
        $this->id          = $id;
        $this->email       = $email;
        $this->password    = $password;
        $this->accessToken = $accessToken;
    }

    public static function create(array $data): self
    {
        return new static(
            $data["id"],
            $data["email"],
            $data["password"],
            $data["access_token"] ?? null,
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function accessToken(): ?string
    {
        return $this->accessToken;
    }
}
