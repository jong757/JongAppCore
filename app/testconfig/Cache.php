<?php
// app/Config/Cache.php
return [
    'file' => [
        'ReflectionPath' => \App\Library\Utilities\FileCache::class, // 映射文件缓存类
        'cache_dir' => 'cache', // 缓存文件存储目录
    ],
    'redis' => [
        'ReflectionPath' => \App\Library\Utilities\RedisCache::class, // 映射 Redis 缓存类
        'port' => 6379,
        'cache_dir' => 2,   // 数据库选择 1~15 号
    ],
	'da' => '110',
];
