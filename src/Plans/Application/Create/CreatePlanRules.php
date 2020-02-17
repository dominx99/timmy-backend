<?php declare(strict_types=1);

namespace App\Plans\Application\Create;

use App\Shared\Contracts\Validation\Rules;
use Respect\Validation\Validator as v;

final class CreatePlanRules implements Rules
{
    public function rules(): array
    {
        return [
            "time_meter_id" => v::notEmpty(),
            "start_date"    => v::notEmpty(),
            "end_date"      => v::notEmpty(),
        ];
    }
}
