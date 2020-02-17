<?php declare(strict_types=1);

namespace App\Accounts\Application\Auth;

use App\Shared\Contracts\Validation\Rules;
use Respect\Validation\Validator as v;

final class AuthRules implements Rules
{
    public function rules(): array
    {
        return [
            "grant_type" => v::notEmpty(),
        ];
    }
}
