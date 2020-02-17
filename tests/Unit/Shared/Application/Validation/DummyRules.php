<?php declare(strict_types=1);

namespace Tests\Unit\Shared\Application\Validation;

use App\Shared\Contracts\Validation\Rules;
use Respect\Validation\Validator as v;

final class DummyRules implements Rules
{
    public function rules(): array
    {
        return [
            'name' => v::notEmpty(),
        ];
    }
}
