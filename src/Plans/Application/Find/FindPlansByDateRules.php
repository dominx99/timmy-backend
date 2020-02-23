<?php declare(strict_types=1);

namespace App\Plans\Application\Find;

use App\Shared\Contracts\Validation\Rules;
use Respect\Validation\Validator as v;

final class FindPlansByDateRules implements Rules
{
    public function rules(): array
    {
        return [
            "date" => v::notEmpty(),
        ];
    }
}
