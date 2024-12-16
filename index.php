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
