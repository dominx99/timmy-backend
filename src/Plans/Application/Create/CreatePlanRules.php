<?php declare(strict_types=1);

namespace App\Plans\Application\Create;

use App\Shared\Contracts\Validation\Rules;
use Respect\Validation\Validator as v;

final class CreatePlanRules implements Rules
{
    public function rules(): array
    {
        return [
            "timeMeterId" => v::notEmpty(),
            "startDate"   => v::notEmpty(),
            "endDate"     => v::notEmpty(),
        ];
    }
}
