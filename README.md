MVC目录结构和架构设计，采用了一些不同的组织方式：

### 结构
```
JongAppCore
├─ app 					主要应用程序代码所在的目录。
│  ├─ Composer			Composer 项目
│  ├─ Config 			配置文件目录，存放应用程序的配置文件。
│  ├─ Library 			库文件目录，存放自定义的库文件。
│  │  ├─ Helpers 		存放一些辅助函数或工具类，这些函数或类可以在整个项目中重复使用。
│  │  ├─ Interfaces 	存放接口定义，用于定义一些通用的接口规范。
│  │  ├─ Middleware 	存放中间件类，用于处理请求和响应之间的逻辑
│  │  ├─ Routes 		路由文件目录，存放应用程序的路由配置。
│  │  ├─ Services 		服务文件目录，存放应用程序的服务类。
│  │  ├─ Traits 		存放一些可以在多个类中复用的特性（Traits）。
│  │  └─ Utilities 		存放应用程序类,composer的src的作用
│  ├─ Resources 		资源文件目录。
│  │ ├─ Lang 			语言文件目录，存放多语言支持的文件。
│  │ └─ Views 			视图文件目录，存放错误类型如404 500 等友好显示。
│  ├─ Models			模型文件目录，存放应用程序的数据模型。 
│  ├─ Views 			视图文件目录，存放前端视图文件。
│  └─ Core.php 			核心文件入口
├─ cache 				缓存文件目录。
│  └─ logs 				日志文件目录，存放应用程序的日志文件。
├─ index.php 			应用程序的入口文件。
├─ README.md 			项目的说明文件，通常包含项目的简介、安装和使用说明。
├─ static 				静态文件目录，存放CSS、JavaScript、图片等静态资源。
└─ upload 				上传存放目录
```

### 目录结构说明：
- **app**: 主要应用程序代码所在的目录。
  - **Config**: 配置文件目录，存放应用程序的配置文件。
  - **Core.php**: 核心文件，可能包含应用程序的核心逻辑。
  - **Library**: 库文件目录，存放自定义的库文件。
    - **Helpers**: 存放一些辅助函数或工具类，这些函数或类可以在整个项目中重复使用。
    - **Interfaces**: 存放接口定义，用于定义一些通用的接口规范。
    - **Middleware**: 存放中间件类，用于处理请求和响应之间的逻辑。
    - **Resources**: 资源文件目录。
      - **Lang**: 语言文件目录，存放多语言支持的文件。
      - **Views**: 视图文件目录，存放错误类型如404 500 等友好显示。
    - **Routes**: 路由文件目录，存放应用程序的路由配置。
    - **Services**: 服务文件目录，存放应用程序的服务类。
    - **Traits**: 存放一些可以在多个类中复用的特性（Traits）。
  - **Models**: 模型文件目录，存放应用程序的数据模型。
  - **Views**: 视图文件目录，存放前端视图文件。
  
- **cache**: 缓存文件目录。
  - **logs**: 日志文件目录，存放应用程序的日志文件。
- **index.php**: 应用程序的入口文件。
- **README.md**: 项目的说明文件，通常包含项目的简介、安装和使用说明。
- **static**: 静态文件目录，存放CSS、JavaScript、图片等静态资源。
- **upload**: 上传文件目录，存放用户上传的文件。

### 伪静态
```
location /{
    if (!-e $request_filename) {
       rewrite  ^(.*)$  /index.php/$1  last;
       break;
    }
}
```


### 架构设计原则：
1. **分层架构**: 将应用程序分为不同层次，如控制器层、服务层、数据访问层等，增强代码的可读性和可维护性。
2. **依赖注入**: 使用依赖注入来管理类之间的依赖关系，增强代码的可测试性和可维护性。
3. **使用中间件**: 在路由和控制器之间使用中间件来处理通用任务，如身份验证、日志记录等。
4. **模块化设计**: 将不同功能模块分开，增强代码的可维护性和可扩展性。
5. **RESTful API**: 如果需要提供API接口，遵循RESTful设计原则，确保API的简洁性和一致性。


### Composer命令和说明
1. `composer init`：初始化一个新的 Composer 项目并创建一个 `composer.json` 文件。
2. `composer install`：根据 `composer.json` 文件安装项目的所有依赖包。
3. `composer update`：更新项目的所有依赖包到最新版本，并更新 `composer.lock` 文件。
4. `composer require <package>`：添加一个新的依赖包到项目中，并更新 `composer.json` 和 `composer.lock` 文件。
5. `composer remove <package>`：从项目中移除一个依赖包，并更新 `composer.json` 和 `composer.lock` 文件。
6. `composer dump-autoload`：重新生成 Composer 的自动加载文件。
7. `composer show`：显示项目中已安装的所有依赖包。
8. `composer show <package>`：显示指定依赖包的详细信息。
9. `composer search <keyword>`：搜索包含指定关键字的依赖包。
10. `composer validate`：验证 `composer.json` 文件的语法和格式是否正确。
11. `composer outdated`：显示项目中已过时的依赖包。
12. `composer self-update`：更新 Composer 自身到最新版本。
13. `composer clear-cache`：清除 Composer 的缓存。

### 目前完成项和配置说明

**composer**
- 已经自动加载 (全局可用)

**常量加载**
- 系统常量 \App\Library\Middleware\ConstantLoader::loadSystemConstants();
- 模型常量 \App\Library\Middleware\ConstantLoader::loadModuleConstants('ModuleA');

**函数加载**
- 系统函数 \App\Library\Middleware\FunctionLoader::loadSystemFunctions();
- 模型函数 \App\Library\Middleware\FunctionLoader::loadModuleFunction('ModuleA');

**配置** (项增删改查)

```
// 使用配置应用程序
use App\Library\Utilities\Config;
```

1. 使用 `Config::get()` 获取配置项。
```
Config::get('Cache');// 获取整个 'Cache' 配置命名空间
Config::get('Cache', 'default');// 获取 'Cache' 配置下的 'default' 配置项
Config::get('Cache', 'redis.host');// 获取 'Cache' 配置下嵌套的 'redis.host' 配置项
```
2. 使用 `Config::set()` 设置或修改配置项。

```
Config::set('Cache', 'default', 'redis');// 修改 'Cache' 配置下的 'default' 配置项为 'redis'
Config::set('Cache', 'redis.host', '192.168.1.1');// 修改嵌套配置项 'Cache.redis.host'
Config::set('Cache', 'redis', [// 修改或添加 'Cache' 配置下的 'redis' 配置项
	'host' => '127.0.0.1',
	'port' => 6380,  // 修改端口
	'prefix' => 'new_prefix_',
]);
```
		
3. 使用 `Config::delete()` 删除配置项或整个命名空间的配置。

```
Config::delete('Cache', 'default');// 删除 'Cache' 配置下的 'default' 配置项
Config::delete('Cache', 'redis.host');// 删除嵌套配置项 'Cache.redis.host'
Config::delete('Cache');// 删除整个 'Cache' 配置命名空间
```
5. 使用 `Config::save()` 保存修改后的配置到文件。

```
Config::save('Cache');// 保存修改后的 'Cache' 配置到文件
```

**缓存**

```
// 获取 Redis 类型的缓存实例
$redisCacheManager = \App\Library\Services\CacheManager::instance('redis');
$redisCacheManager->set('user_123', ['name' => 'John Doe']);
$redisUserData = $redisCacheManager->get('user_123');
echo 'Redis Cache - User Data: ';
print_r($redisUserData);

// 获取文件缓存实例
$fileCacheManager = \App\Library\Services\CacheManager::instance('file');
$fileCacheManager->set('user_123', ['name' => 'Jane Doe']);
$fileUserData = $fileCacheManager->get('user_123');
echo 'File Cache - User Data: ';
print_r($fileUserData);

// 删除缓存
$fileCacheManager->delete('user_123');

// 清除所有缓存
$fileCacheManager->clear(); 
$redisCacheManager->clear();
```
