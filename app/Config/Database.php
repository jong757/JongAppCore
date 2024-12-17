<?php
// app/Config/Database.php

return [
    'mysql' => [
        'ReflectionPath' => 'App\\Library\\Utilities\\MysqlConnection', // 指定 MySQL 连接类
        'host' => '127.0.0.1',
        'port' => 3306,
        'dbname' => 'app',
        'username' => 'root',
        'password' => 'root',
        'charset' => 'utf8mb4',
    ],
    'sqlite' => [
        'ReflectionPath' => 'App\\Library\\Utilities\\SqliteConnection', // 指定 SQLite 连接类
        'database' => '/path/to/database.sqlite',
    ],
    'sqlserver' => [
        'ReflectionPath' => 'App\\Library\\Utilities\\SqlServerConnection', // 指定 SQL Server 连接类
        'host' => 'localhost',
        'port' => 1433,
        'dbname' => 'my_database',
        'username' => 'sa',
        'password' => 'password',
    ],
];
