<?php
// app/Config/Cache.php
return [
    'file' => [
        'cache_dir' => 'cache', // 缓存文件存储目录
    ],
    'redis' => [
        'host' => '127.0.0.1',
        'port' => 6379,
		'password' => '',  // Redis 密码
		'database' => 2 //数据库选择 1~15 号
    ]
];

