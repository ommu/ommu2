<?php
$params = \yii\helpers\ArrayHelper::merge(
	(\app\components\Application::isDev() && (is_readable(__DIR__ . '/../../../protected/config/params-dev.php')))?
		require(__DIR__ . '/../../../protected/config/params-dev.php'):
		require(__DIR__ . '/../../../protected/config/params.php'),
	(\app\components\Application::isDev() && (is_readable(__DIR__ . '/params-dev.php')))?
		require(__DIR__ . '/params-dev.php'):
		require(__DIR__ . '/params.php')
);
$bn = \app\components\Application::getAppId();

$config = [
	'name' => 'OMMU by sudaryanto.id',
	'id' => 'basic',
	'runtimePath' => dirname(__DIR__) . '/runtime',
	'bootstrap' => [],
	'components' => [
		'request' => [
			// !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
			'cookieValidationKey' => ''
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
		'jwt' => [
			'class'    => 'app\components\Jwt',
			'key'      => '',
			'issuer'   => '',
			'audiance' => '',
			'id'       => '',
		],
	],
	'params' => $params,
];

return $config;
