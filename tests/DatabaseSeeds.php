<?php declare(strict_types=1);

namespace Tests;

use Phinx\Config\Config;
use Phinx\Migration\Manager;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;

trait DatabaseSeeds
{
    public function seed(): void
    {
        $path        = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'phinx.php';
        $configArray = require $path;

        $config  = new Config($configArray);
        $manager = new Manager($config, new StringInput(' '), new NullOutput());

        $manager->seed('dev');
    }
}
