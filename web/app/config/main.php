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
		'authManager' => [
			'class'             => 'mdm\admin\components\DbManager',
			'assignmentTable'   => 'ommu_core_auth_assignment',
			'itemTable'         => 'ommu_core_auth_item',
			'itemChildTable'    => 'ommu_core_auth_item_child',
			'ruleTable'         => 'ommu_core_auth_rule',
		]
	],
	'params' => $params,
];

return $config;
