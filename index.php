<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED | E_STRICT);
date_default_timezone_set('Asia/Jakarta');
ini_set('post_max_size', '8M');
ini_set('upload_max_filesize', '16M');

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/protected/vendor/autoload.php');
require(__DIR__ . '/protected/vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/protected/config/web.php');

$app = new yii\web\Application($config);
$app->run();