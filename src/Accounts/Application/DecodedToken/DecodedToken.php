<?php declare(strict_types=1);

namespace App\Accounts\Application\DecodedToken;

use App\Shared\Exceptions\SystemException;

final class DecodedToken
{
    private string $id;

    public function __construct(array $data)
    {
        if (empty($data["id"])) {
            throw new SystemException("Invalid token data");
        }

        $this->id = $data["id"];
    }

    public function id(): string
    {
        return $this->id;
    }
}
