<?php declare(strict_types=1);

namespace Tests\Unit\Shared\Application\Validation;

use Tests\BaseTestCase;
use App\Shared\Application\Validation\Validator;
use App\Shared\Exceptions\ValidationException;

final class ValidatorTest extends BaseTestCase
{
    private Validator $validator;

    public function setUp(): void
    {
        parent::setUp();

        $this->validator = new Validator();
    }

    /** @test */
    public function that_validates()
    {
        $this->validator->validate([
            "name" => "Work",
        ], new DummyRules());

        $this->addToAssertionCount(1);
    }

    /** @test */
    public function that_throws_validation_exception()
    {
        $this->expectException(ValidationException::class);

        $this->validator->validate([
            "name" => "",
        ], new DummyRules());
    }

    /** @test */
    public function that_throws_validation_exception_on_empty_params()
    {
        $this->expectException(ValidationException::class);

        $this->validator->validate([], new DummyRules());
    }

    /** @test */
    public function that_throws_exception_with_valid_errors()
    {
        $expectErrors = [
            'name' => ["Name must not be empty"],
        ];

        try {
            $this->validator->validate([], new DummyRules());
        } catch (ValidationException $e) {
            $this->assertSame($expectErrors, $e->messages());
        }
    }
}
