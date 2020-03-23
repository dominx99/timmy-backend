<?php declare(strict_types=1);

namespace App\Plans\Application\Rules;

use App\Shared\Contracts\Validation\Rules;
use Respect\Validation\Validator as v;

final class FindPlansByPeriodRules implements Rules
{
    public function rules(): array
    {
        return [
            "startDate" => v::notEmpty(),
            "endDate"   => v::notEmpty(),
        ];
    }
}
