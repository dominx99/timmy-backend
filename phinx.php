<?php declare(strict_types = 1);

use Dotenv\Dotenv;

$dotenv= Dotenv::createImmutable(__DIR__);
$dotenv->load();

return [
    'paths' => [
        'migrations' => 'db/migrations',
        'seeds'      => 'db/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'migrations',
        'default_database'        => "dev",
        "dev" => [
            'adapter'      => "mysql",
            'host'         => getenv('DB_HOST'),
            'name'         => getenv('DB_NAME'),
            'user'         => getenv('DB_USER'),
            'pass'         => getenv('DB_PASSWORD'),
            'port'         => getenv('DB_PORT'),
            'charset'      => getenv('DB_CHARSET'),
            'collation'    => getenv('DB_COLLATION'),
        ],
    ],
    'version_order' => 'creation',
];
