<?php
// 系统路径常量
define('PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
// 定义应用路径，确保仅定义一次
defined('APP_PATH') or define('APP_PATH', PATH . 'app' . DIRECTORY_SEPARATOR);

// 引入 Core.php 文件
require APP_PATH . 'Core.php';

use App\Core;
use App\Library\Utilities\Config;
// 先注册自动加载器
Core::register();
// 实例化 Core 类
$core = new Core();


/* 
* 数据库
*/
$DatabaseManager = new App\Library\Services\DatabaseService;
// 获取数据库连接
$connection = $DatabaseManager->getConnection();
$users = $connection->read('db_admin',['roleid' => 1]);
// $sql = "SELECT * FROM db_admin WHERE id = ?";
// $result = $connection->query($sql, [1]);
print_r($users);


/* 
*使用Config 设置配置
*/

// 设置自定义目录
// Config::setDirectory('testconfig');

// 获取配置项
// $cacheConfig = Config::get('Cache'); // 获取整个配置
// $defaultCache = Config::get('Cache', 'default'); // 获取 'default' 配置项




/* 
*使用 CacheManager 获取缓存实例
*/
// $cacheManager = new \App\Library\Services\CacheManager('redis');  // 可以传入缓存类型 'file' 或 'redis'
// // 设置缓存
// $cacheManager->set('user_123/12101', ['name' => 'John Doe', 'age' => 30]);
// 获取缓存
// print_r($cacheManager->get('user_123/12'));

//删除缓存
// $cacheManager->delete('user_123/12');

//清除所有缓存
// $cacheManager->clear();


/* 
*映射路径和文件
*/
// //已经运行的类路径映射
// print_r(Core::getClassMap());
// //已经运行的文件路径映射
// print_r(Core::getLoadedFiles());
// 在此之后，你的类会通过自动加载器自动加载


/* 
*测试函数常量
*/
// echo generate_unique_id();  // 测试函数调用
// print_r(SYSTEM_CONSTANT_2); // 测试常量调用


/* 
*测试请求类型
*/
/* $method = $_SERVER['REQUEST_METHOD'];
print_r($method); */


/* 
*测试 Composer 自动加载的类是否正常工作
*/
/* use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log = new Logger('name');
$log->pushHandler(new StreamHandler('your.log', Logger::WARNING));

$log->warning('This is a warning'); */