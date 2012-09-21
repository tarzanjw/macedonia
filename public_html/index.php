<?php
define('ROOT_PATH', realpath(__DIR__.'/..'));
define('HOME_PATH', ROOT_PATH.'/public_html');
define('CODE_PATH', ROOT_PATH.'/protected');
define('HOME_URL', '');

// change the following paths if necessary
$yii=ROOT_PATH.'/yii/framework/yii.php';
$config=CODE_PATH.'/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once CODE_PATH.'/config/constants.php';
require_once($yii);
Yii::createWebApplication($config)->run();
