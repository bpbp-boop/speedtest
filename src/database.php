<?php

$host = 'host=' . SQL_HOSTNAME;
$database = 'dbname=' . SQL_DATABASE;
$db = null;

switch (SQL_TYPE) {
    case 'mysql':
        $type = 'mysql';
        $db = new PDO("{$type}:{$host};{$database};", SQL_USERNAME, SQL_PASSWORD);
        break;
    case 'postgresql':
        $type = 'pgsql';
        $db = new PDO("{$type}:{$host};{$database};", SQL_USERNAME, SQL_PASSWORD);
        break;
    case 'sqlite':
        $db = new PDO('sqlite:' . SQLITE_DB_FILE);
        break;
    default:
        exit;
}
