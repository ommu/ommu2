<?php
$database = (\app\components\Application::isDev() && (is_readable(__DIR__ . '/database-dev.php')))?
	require(__DIR__ . '/database-dev.php'):
	require(__DIR__ . '/database.php');
$bn = \app\components\Application::getAppId();

$config = [
	'id' => 'basic',
	'name' => 'OMMU by sudaryanto.id',
	'basePath' => dirname(__DIR__),
	'vendorPath' => dirname(__DIR__) . '/vendor',
	'runtimePath' => dirname(__DIR__) . '/runtime',
	'bootstrap' => [
		'log',
		'app\components\bootstrap\ThemeControllerHandle'
	],
	'aliases' => [
		'@bower'      => '@vendor/bower-asset',
		'@npm'        => '@vendor/npm-asset',
		'@ommu'       => '@vendor/ommu',
		'@webpublic'  => '/public',
	],
	'components' => [
		'request' => [
			'csrfParam' => $bn . '_csrf',
		],
		'cache' => [
			'class' => 'yii\caching\FileCache',
		],
		'user' => [
			'class'             => 'app\modules\user\components\User',
			'identityClass'     => 'app\modules\user\models\Users',
			'enableAutoLogin'   => true,
			'loginUrl'          => ['/login'],
			'identityCookie'    => ['name' => $bn . '_identity', 'httpOnly' => true],
			'authTimeout'       => 7 * 24 * 3600,
		],
		'authManager' => [
			'class'             => 'mdm\admin\components\DbManager',
			'assignmentTable'   => 'ommu_core_auth_assignment',
			'itemTable'         => 'ommu_core_auth_item',
			'itemChildTable'    => 'ommu_core_auth_item_child',
			'ruleTable'         => 'ommu_core_auth_rule',
		],
		'formatter' => [
			'class'             => 'app\components\i18n\Formatter',
			'dateFormat'        => 'php:d-M-Y',
			'datetimeFormat'    => 'php:d-M-Y H:i:s',
			'timeFormat'        => 'php:H:i:s',
			'thousandSeparator' => '.',
			'decimalSeparator'  => ',',
			'locale'            => 'id',
			'defaultTimeZone'   => 'Asia/Jakarta',
			'currencyCode'      => 'IDR',
		 
		],
		'mailer' => [
			'class' => 'yii\swiftmailer\Mailer',
			// send all mails to a file by default. You have to set
			// 'useFileTransport' to false and configure a transport
			// for the mailer to send real emails.
			'useFileTransport' => true,
		],
		'log' => [
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets' => [
				[
					'class' => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],
			],
		],
		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'rules' => [
				//a standard rule mapping '/' to 'site/index' action
				'' 																	=> 'site/index',
				'<t:[\w\-]+>-<id:\d+>'												=> 'site/view',
				//a standard rule mapping '/login' to 'site/login', and so on
				'<action:(login|logout)>' 											=> 'site/<action>',
			],
		],
		'view' => [
			'class' => '\app\components\View',
		],
		'setting' => [
			'class'    => '\app\components\SettingManager',
			'moduleId' => 'base',
		],
		'meta' => [
			'class'    => '\app\components\SettingManager',
			'moduleId' => 'meta',
		],
	],
	'modules' => [
		'redactor' => [
			'class'                 => 'yii\redactor\RedactorModule',
			'uploadDir'             => '@public',
			'uploadUrl'             => '@web/public',
			'imageAllowExtensions'  => ['jpg','png']
		],
	],
];

$config = \yii\helpers\ArrayHelper::merge(
	$config,
	$database
);

return $config;
