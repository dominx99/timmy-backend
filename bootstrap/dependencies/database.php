<?php declare(strict_types = 1);

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

$container->set(Connection::class, fn() => DriverManager::getConnection([
    'dbname'   => getenv("DB_NAME"),
    'user'     => getenv("DB_USER"),
    'password' => getenv("DB_PASSWORD"),
    'host'     => getenv("DB_HOST"),
    'driver'   => getenv("DB_DRIVER"),
]));
