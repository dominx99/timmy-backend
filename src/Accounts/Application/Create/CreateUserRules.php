<?php declare(strict_types=1);

namespace App\Accounts\Application\Create;

use App\Shared\Contracts\Validation\Rules;
use Respect\Validation\Validator as v;

final class CreateUserRules implements Rules
{
    public function rules(): array
    {
        return [
            "email"    => v::notEmpty()->email(),
            "password" => v::notEmpty(),
        ];
    }
}
