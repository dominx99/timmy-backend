<?php declare(strict_types=1);

namespace App\TimeMeters\Application\Create;

use App\Shared\Contracts\Validation\Rules;
use Respect\Validation\Validator as v;

final class CreateTimeMeterRules implements Rules
{
    public function rules(): array
    {
        return [
            'name' => v::notEmpty(),
        ];
    }
}
