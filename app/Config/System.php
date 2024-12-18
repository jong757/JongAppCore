<?php
// app/Config/System.php
return [
    'cache' => 'file', // 默认缓存类型，可选值：file, redis
    'db' => 'mysql', // 默认缓存类型，可选值：mysql, sqlite, sqlserver
	'debug' => 0, // 开启调试 需要确保 php.ini 或其他配置没有强制开启 log_errors
];
