<?php

define('APP_PATH', realpath(dirname(__FILE__)));//设置项目根目录路径

require APP_PATH.'/vendor/autoload.php';

use Yeo\Core\YeoCore;

$yeocore = new YeoCore;
$yeocore->run();