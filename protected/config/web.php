<?php
$params = (\app\components\Application::isDev() && (is_readable(__DIR__ . '/params-dev.php')))?
	require(__DIR__ . '/params-dev.php'):
	require(__DIR__ . '/params.php');
$database = (\app\components\Application::isDev() && (is_readable(__DIR__ . '/database-dev.php')))?
	require(__DIR__ . '/database-dev.php'):
	require(__DIR__ . '/database.php');
$bn = \app\components\Application::getAppId();

$config = [
	'name' => 'OMMU',
	'id' => 'basic',
	'bootstrap' => [
		'app\components\bootstrap\ModuleAutoLoader',
		'app\components\bootstrap\ThemeControllerHandle',
		'log'
	],
	'basePath' => dirname(__DIR__),
	'vendorPath' => dirname(dirname(__DIR__)) . '/protected/vendor',
	'runtimePath' => dirname(dirname(__DIR__)) . '/protected/runtime',
	'aliases' => [
		'@bower' => '@vendor/bower-asset',
		'@npm'   => '@vendor/npm-asset',
		'@ommu'  => '@vendor/ommu',
	],
	'components' => [
		'request' => [
			// !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
			'cookieValidationKey' => 'LLcrvjg0PkdtmckBnmtmxmHMzdV1UZ_2',
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
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		'formatter' => [
			'class'          => 'app\components\i18n\Formatter',
			'dateFormat'     => 'php:d-M-Y',
			'datetimeFormat' => 'php:d-M-Y H:i:s',
			'timeFormat'     => 'php:H:i:s',
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
				//a standard rule mapping '/login' to 'site/login', and so on
				'<action:(login|logout)>' 											=> 'site/<action>',
			],
		],
		'authManager' => [
			'class'             => 'mdm\admin\components\DbManager',
			'assignmentTable'   => 'ommu_core_auth_assignment',
			'itemTable'         => 'ommu_core_auth_item',
			'itemChildTable'    => 'ommu_core_auth_item_child',
			'ruleTable'         => 'ommu_core_auth_rule',
		],
		'view' => [
			'class' => '\app\components\View',
		],
		'jwt' => [
			'class'    => 'app\components\Jwt',
			'key'      => 'LLcrvjg0PkdtmckBnmtmxmHMzdV1UZ_2',
			'issuer'   => 'http://ommu.id',
			'audiance' => 'http://ommu.id',
			'id'       => 'LLcrvjg0PkdtmckB2',
		],
		'assetManager' => [
			'class'    => '\app\components\AssetManager',
		],
		'setting' => [
			'class'    => '\app\components\SettingManager',
			'moduleId' => 'base',
		],
		'moduleManager' => [
			'class'        => '\app\components\ModuleManager',
			'createBackup' => true,
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
	'params' => $params,
];

$config = yii\helpers\ArrayHelper::merge(
	$config,
	$database
);

if (YII_ENV_DEV) {
	// configuration adjustments for 'dev' environment
	// $config['bootstrap'][] = 'debug';
	// $config['modules']['debug'] = [
	//     'class' => 'yii\debug\Module',
	//     // uncomment the following to add your IP if you are not connecting from localhost.
	//     //'allowedIPs' => ['127.0.0.1', '::1'],
	// ];

	$config['bootstrap'][] = 'gii';
}

return $config;
