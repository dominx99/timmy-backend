<?php declare(strict_types=1);

namespace Tests;

use Doctrine\DBAL\Connection;
use Phinx\Config\Config;
use Phinx\Console\PhinxApplication;
use Phinx\Migration\Manager;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;
use PHPUnit\Framework\Assert;

trait DatabaseMigrations
{
    public function migrate(): void
    {
        $path        = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'phinx.php';
        $configArray = require $path;

        $config  = new Config($configArray);
        $manager = new Manager($config, new StringInput(' '), new NullOutput());

        $manager->migrate('dev');
    }

    protected function rollback()
    {
        $app = new PhinxApplication();
        $app->doRun(new StringInput("rollback -e dev -t 0"), new NullOutput());
    }

    protected function assertDatabase(string $table, array $data): bool
    {
        $connection = $this->container->get(Connection::class);
        $qb = $connection->createQueryBuilder();

        $qb
            ->select('*')
            ->from($table);

        foreach ($data as $key => $value) {
            $qb
                ->andWhere("{$key} = :{$key}")
                ->setParameter($key, $value);
        }

        return (bool) $connection->fetchAssoc($qb->getSQL(), $qb->getParameters());
    }

    protected function assertDatabaseHas(string $table, array $data): void
    {
        Assert::assertTrue($this->assertDatabase($table, $data));
    }

    protected function assertDatabaseMissing(string $table, array $data): void
    {
        Assert::assertFalse($this->assertDatabase($table, $data));
    }
}
