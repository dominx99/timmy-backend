<?php declare(strict_types=1);

namespace App\Shared\Application\Validation;

use App\Shared\Contracts\Validation\Rules;
use App\Shared\Exceptions\ValidationException;
use Respect\Validation\Exceptions\NestedValidationException;
use App\Shared\Contracts\Validation\ValidatorContract;

final class Validator implements ValidatorContract
{
    public function validate(array $params, Rules $rules): void
    {
        $errors = new ErrorBag();

        foreach ($rules->rules() as $field => $rule) {
            try {
                $rule
                    ->setName(ucfirst($field))
                    ->assert($params[$field] ?? null);
            } catch (NestedValidationException $e) {
                $errors->add($field, new ErrorsCollection($e->getMessages()));
            }
        }

        if (! $errors->empty()) {
            throw ValidationException::withMessages($errors->toArray());
        }
    }
}
