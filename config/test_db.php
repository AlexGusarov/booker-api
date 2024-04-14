<?php

require __DIR__ . '/../vendor/autoload.php';

$testDatabaseUrl = getenv('TEST_DATABASE_URL');
if ($testDatabaseUrl === false) {
    throw new Exception('TEST_DATABASE_URL не определена в переменных окружения.');
}

$dbParts = parse_url($testDatabaseUrl);
if (!isset($dbParts['host']) || !isset($dbParts['user']) || !isset($dbParts['pass']) || !isset($dbParts['path'])) {
    throw new Exception('TEST_DATABASE_URL некорректна или неполна.');
}

if (!isset($dbParts['port'])) {
    $dbParts['port'] = 5432;
}

$dsn = sprintf(
    "pgsql:host=%s;port=%d;dbname=%s",
    $dbParts['host'],
    $dbParts['port'],
    ltrim($dbParts['path'], '/')
);

putenv("TEST_DATABASE_DSN=pgsql:host={$dbParts['host']};dbname=" . ltrim($dbParts['path'], '/'));
putenv("TEST_DATABASE_USER={$dbParts['user']}");
putenv("TEST_DATABASE_PASSWORD={$dbParts['pass']}");

error_log("DSN: " . $dsn);
error_log("Username: " . $dbParts['user']);
error_log("Password: " . $dbParts['pass']);

return [
    'class' => 'yii\db\Connection',
    'dsn' => $dsn,
    'username' => $dbParts['user'],
    'password' => $dbParts['pass'],
    'charset' => 'utf8',
];

