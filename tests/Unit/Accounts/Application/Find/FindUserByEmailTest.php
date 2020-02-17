<?php declare(strict_types=1);

namespace Tests\Unit\Accounts\Application\Find;

use Tests\BaseTestCase;
use Tests\DatabaseMigrations;
use Tests\DatabaseSeeds;
use App\Accounts\Domain\UserNotFoundException;
use App\Accounts\Application\Find\FindUserByEmailQuery;

final class FindUserByEmailTest extends BaseTestCase
{
    use DatabaseMigrations, DatabaseSeeds;

    /** @test */
    public function that_finds_user()
    {
        $user = $this->queryBus->handle(new FindUserByEmailQuery("example@test.com"));

        $this->assertSame("example@test.com", $user->email());
    }

    /** @test */
    public function that_throws_user_not_found_excpetion_when_not_found()
    {
        $this->expectException(UserNotFoundException::class);

        $this->queryBus->handle(new FindUserByEmailQuery("example"));
    }
}
