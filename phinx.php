<?php declare(strict_types = 1);

use Dotenv\Dotenv;

$config = [];

if (getenv("APP_ENV") === "production") {
    $config = parse_url(getenv("CLEARDB_DATABASE_URL"));

    $config = [
        "host"     => $config['host'],
        "user"     => $config['user'],
        "password" => $config['pass'],
        "dbname"   => substr($config["path"], 1),
    ];
} else {
    $dotenv= Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $config = [
        'host'     => getenv("DB_HOST"),
        'user'     => getenv("DB_USER"),
        'password' => getenv("DB_PASSWORD"),
        'dbname'   => getenv("DB_NAME"),
    ];
}

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
            'host'         => $config["host"],
            'name'         => $config["dbname"],
            'user'         => $config["user"],
            'pass'         => $config["password"],
            'port'         => getenv('DB_PORT'),
            'charset'      => getenv('DB_CHARSET'),
            'collation'    => getenv('DB_COLLATION'),
        ],
    ],
    'version_order' => 'creation',
];
