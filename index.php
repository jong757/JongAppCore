<?php
// 系统路径常量
define('PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
// 定义应用路径，确保仅定义一次
defined('APP_PATH') or define('APP_PATH', PATH . 'app' . DIRECTORY_SEPARATOR);

// 引入 Core.php 文件
require APP_PATH . 'Core.php';

use App\Core;

// 先注册自动加载器
Core::register();
// 实例化 Core 类
$core = new Core();

// // 获取 Redis 类型的缓存实例
// $redisCacheManager = \App\Library\Services\CacheManager::instance('redis');
// $redisCacheManager->set('user_123/123/222', ['name' => 'John Doe']);
// $redisUserData = $redisCacheManager->get('user_123/123/222');

// // 输出 Redis 缓存中的用户数据
// print_r($redisUserData);


// // 获取文件缓存实例
// $fileCacheManager = \App\Library\Services\CacheManager::instance('file');
// $fileCacheManager->set('user_123/223', ['name' => 'Jane Doe']);
// $fileUserData = $fileCacheManager->get('user_123/223');

// print_r($fileUserData);

// // 删除缓存
// $fileCacheManager->delete('user_123');

// // 清除所有缓存
// $fileCacheManager->clear();
// $redisCacheManager->clear();



// //已经运行的类路径映射
// print_r(Core::getClassMap());
// //已经运行的文件路径映射
// print_r(Core::getLoadedFiles());
// 在此之后，你的类会通过自动加载器自动加载

// echo generate_unique_id();  // 测试函数调用
// print_r(SYSTEM_CONSTANT_2); // 测试常量调用

//测试请求类型
/* $method = $_SERVER['REQUEST_METHOD'];
print_r($method); */

// 测试 Composer 自动加载的类是否正常工作
/* use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log = new Logger('name');
$log->pushHandler(new StreamHandler('your.log', Logger::WARNING));

$log->warning('This is a warning'); */