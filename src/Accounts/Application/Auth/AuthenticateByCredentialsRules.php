<?php declare(strict_types=1);

namespace App\Accounts\Application\Auth;

use App\Shared\Contracts\Validation\Rules;
use Respect\Validation\Validator as v;

final class AuthenticateByCredentialsRules implements Rules
{
    public function rules(): array
    {
        return [
            'email'    => v::notEmpty(),
            'password' => v::notEmpty(),
        ];
    }
}
