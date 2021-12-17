<?php
use Dotenv\Dotenv;
use app\core\Application;
use app\models\User;

require_once __DIR__.'/vendor/autoload.php';
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$config = [
    'userClass' => User::class,
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD']
    ]
];

$app = new Application(__DIR__, $config);

$app->db->dropMigrations();
$app->db->applyMigrations();
