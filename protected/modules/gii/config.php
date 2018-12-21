<?php
return [
	'id'           => 'gii',
	'class'        => \app\modules\gii\Module::className(),
	'bootstrap'    => true,
	'allowedIPs' => ['127.0.0.1', '::1', '192.168.3.*', '192.168.2.*'],
	'generators' => [
		'model' => [
			'class' => 'ommu\gii\model\Generator',
			'templates' => [
				'default' => '@app/modules/gii/model/default',
				'gentelella' => '@vendor/ommu/gii/model/gentelella',
			],
		],
		'crud' => [
			'class' => 'ommu\gii\crud\Generator',
			'templates' => [
				'default' => '@app/modules/gii/crud/default',
				'gentelella' => '@vendor/ommu/gii/crud/gentelella',
			],
		],
		'controller' => [
			'class' => 'ommu\gii\controller\Generator',
			'templates' => [
				'default' => '@app/modules/gii/controller/default',
				'gentelella' => '@vendor/ommu/gii/controller/gentelella',
			],
		],
		'module' => [
			'class' => 'ommu\gii\module\Generator',
			'templates' => [
				'default' => '@app/modules/gii/module/default',
				'gentelella' => '@vendor/ommu/gii/module/gentelella',
			],
		],
		'form' => [
			'class' => 'ommu\gii\form\Generator',
			'templates' => [
				'default' => '@app/modules/gii/form/default',
			],
		],
		'extension' => [
			'class' => 'ommu\gii\extension\Generator',
			'templates' => [
				'default' => '@app/modules/gii/extension/default',
			],
		],
		'migration' => [
			'class' => 'app\libraries\gii\migration\Generator',
			'templates' => [
				'default' => '@app/modules/gii/migration/default',
			],
		],
		'api' => [
			'class' => 'app\libraries\gii\api\Generator',
			'templates' => [
				'default' => '@app/modules/gii/api/ecc4',
			],
		],
	]
];
