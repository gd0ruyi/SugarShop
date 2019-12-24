<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用入口文件

// 自定义加入HTML输出头部格式为UTF-8
@header("Content-type:text/html; charset=UTF-8");

// 检测PHP环境
if (version_compare(PHP_VERSION, '5.3.0', '<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG', true);

// 开启调试模式时方可生效，用于常量调试输出调试
define('DEBUG_PRINT_CONSTANTS', false);

// 开启调试模式时方可生效，用于服务器信息调试输出调试
define('DEBUG_PRINT_SERVER', false);

// 定义应用目录
define('APP_PATH', './Application/');

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单
