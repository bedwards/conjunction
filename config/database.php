<?php

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

return [
    'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
    'port' => $_ENV['DB_PORT'] ?? '3307',
    'database' => $_ENV['DB_NAME'] ?? 'conjunction_db',
    'username' => $_ENV['DB_USER'] ?? 'conjuser',
    'password' => $_ENV['DB_PASS'] ?? 'conjpass',
    'charset' => 'utf8mb4',
];
