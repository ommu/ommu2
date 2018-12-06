<?php
Yii::setAlias('@webroot', realpath(__DIR__ . '/../../'));
Yii::setAlias('@app', '@webroot/protected');
Yii::setAlias('@config', '@app/config');
Yii::setAlias('@models', '@app/models');
Yii::setAlias('@modules', '@app/modules');
Yii::setAlias('@vendor', '@app/vendor');
Yii::setAlias('@ommu', '@vendor/ommu');
Yii::setAlias('@public', '@webroot/public');

$params = \app\components\Application::isDev() ? 
	require(__DIR__ . '/params-dev.php') :
	require(__DIR__ . '/params.php');
$database = \app\components\Application::isDev() ? 
	require(__DIR__ . '/database-dev.php') :
	require(__DIR__ . '/database.php');

$production = [
	'name' => 'Ommu',
	'id' => 'basic',
	'basePath' => dirname(__DIR__),
	'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
	'components' => [
		'request' => [
			// !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
			'cookieValidationKey' => 'LLcrvjg0PkdtmckBnmtmxmHMzdV1UZ_2',
		],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		'formatter' => [
			'class'   => 'app\components\i18n\Formatter',
			'dateFormat' => 'php:d-M-Y',
			'datetimeFormat' => 'php:d-M-Y H:i:s',
			'timeFormat' => 'php:H:i:s',
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
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
	'params' => $params,
];

$config = yii\helpers\ArrayHelper::merge(
	$production,
	$database
);

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
