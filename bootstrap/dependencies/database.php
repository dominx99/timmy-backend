<?php declare(strict_types = 1);

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

$config = [];

if (getenv("APP_ENV") === "production") {
    $config = parse_url(getenv("CLEARDB_DATABASE_URL"));

    $config = [
        "host"     => $config['host'],
        "user"     => $config['user'],
        "password" => $config['pass'],
        "dbname"   => substr($url["path"], 1),
    ];
} else {
    $config = [
        'host'     => getenv("DB_HOST"),
        'user'     => getenv("DB_USER"),
        'password' => getenv("DB_PASSWORD"),
        'dbname'   => getenv("DB_NAME"),
    ];
}

$container->set(Connection::class, fn() => DriverManager::getConnection(array_merge($config, [
    'driver' => getenv("DB_DRIVER"),
])));
