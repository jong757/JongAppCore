<?php
// app/Config/Cache.php
return [
    'file' => [
        'ReflectionPath' => 'App\Library\Utilities\FileCache', // 映射文件缓存类
        'cache_dir' => 'cache', // 缓存文件存储目录
    ],
    'redis' => [
        'ReflectionPath' => 'App\Library\Utilities\RedisCache', // 映射 Redis 缓存类
        'host' => '127.0.0.1',
        'port' => 6379,
        'password' => '',  // Redis 密码
        'database' => 2,   // 数据库选择 1~15 号
    ],
	'da' =>'110',
];
