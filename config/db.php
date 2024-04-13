<?php

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$dbUrl = getenv("DATABASE_URL");
if ($dbUrl === false) {
    throw new Exception('DATABASE_URL не определена в переменных окружения.');
}

$dbParts = parse_url($dbUrl);

$dsn = sprintf(
    "pgsql:host=%s;port=%s;dbname=%s;user=%s;password=%s",
    $dbParts['host'],
    $dbParts['port'],
    ltrim($dbParts['path'], '/'),
    $dbParts['user'],
    $dbParts['pass']
);

return [
    'class' => 'yii\db\Connection',
    'dsn' => $dsn,
    'username' => $dbParts['user'],
    'password' => $dbParts['pass'],
    'charset' => 'utf8',
];
