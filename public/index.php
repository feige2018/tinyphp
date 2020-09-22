<?php

/**
 * 创建于 2020-09-20
 * TinyPHP 极简PHP框架
 */

//请修改你的项目路径：
defined('ROOT_PATH') || define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
defined('APP_PATH') || define('APP_PATH', ROOT_PATH . 'app' . DIRECTORY_SEPARATOR);
defined('START_PATH') || define('START_PATH', "/"); //ROUTE URI 从这个目录开始

require_once ROOT_PATH . 'vendor/autoload.php';

$response = (new \tiny\TinyPHP)->run();
$response->send();
$response->end();
