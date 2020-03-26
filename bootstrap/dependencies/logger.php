<?php declare(strict_types = 1);

use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$container->set(LoggerInterface::class, function () {
    $log = new Logger('main');
    $log->pushHandler(new StreamHandler(__DIR__ . '/../../logs/main.log'));

    return $log;
});
