<?php declare(strict_types=1);

namespace Tests\Unit\Accounts\Application\DecodedToken;

use App\Accounts\Application\DecodedToken\DecodedToken;
use App\Shared\Exceptions\SystemException;
use Ramsey\Uuid\Uuid;
use Tests\BaseTestCase;

final class DecodedTokenTest extends BaseTestCase
{
    /** @test */
    public function that_has_valid_data()
    {
        $id = (string) Uuid::uuid4();

        $decoded = new DecodedToken(["id" => $id]);

        $this->assertSame($id, $decoded->id());
    }

    /** @test */
    public function that_throws_exception_on_not_valid_data()
    {
        $id = (string) Uuid::uuid4();

        $this->expectException(SystemException::class);
        $this->expectExceptionMessage("Invalid token data");

        new DecodedToken(["not-id" => $id]);
    }
}
