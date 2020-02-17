<?php

use App\Shared\Infrastructure\JWT\JWTEncoder;
use Phinx\Seed\AbstractSeed;
use Ramsey\Uuid\Uuid;

class UsersSeeder extends AbstractSeed
{
    public function run()
    {
        $userId = (string) Uuid::uuid4();

        $this->insert("users", [
            "id"           => $userId,
            "email"        => "example@test.com",
            "password"     => password_hash("secret", PASSWORD_BCRYPT),
            "access_token" => JWTEncoder::fromArray(["id" => $userId]),
        ]);
    }
}
