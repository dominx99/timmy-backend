<?php declare(strict_types=1);

namespace App\Shared\Contracts\Validation;

interface ValidatorContract
{
    public function validate(array $params, Rules $rules): void;
}
