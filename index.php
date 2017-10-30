<?php

if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');
date_default_timezone_set("Asia/Shanghai");
/**
 * 系统调试设置
 * 项目正式部署后请设置为false
 */
define('APP_DEBUG', true);

/**
 * 设置应用目录
 */
define ( 'APP_PATH', './Application/' );

/**
 * 引入框架核心文件
 */
require './vendor/autoload.php';
require "ThinkPHP/ThinkPHP.php";