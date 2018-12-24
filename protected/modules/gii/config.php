<?php
return [
	'id'           => 'gii',
	'class'        => \app\modules\gii\Module::className(),
	'bootstrap'    => true,
	'allowedIPs'   => ['127.0.0.1', '::1'],
	'generators'   => [
		'model' => [
			'class' => 'ommu\gii\model\Generator',
			'templates' => [
				'default' => '@app/modules/gii/generators/model/default',
				'gentelella' => '@vendor/ommu/gii/model/gentelella',
			],
		],
		'crud' => [
			'class' => 'ommu\gii\crud\Generator',
			'templates' => [
				'default' => '@app/modules/gii/generators/crud/default',
				'gentelella' => '@vendor/ommu/gii/crud/gentelella',
			],
		],
		'controller' => [
			'class' => 'ommu\gii\controller\Generator',
			'templates' => [
				'default' => '@app/modules/gii/generators/controller/default',
				'gentelella' => '@vendor/ommu/gii/controller/gentelella',
			],
		],
		'module' => [
			'class' => 'ommu\gii\module\Generator',
			'templates' => [
				'default' => '@app/modules/gii/generators/module/default',
				'gentelella' => '@vendor/ommu/gii/module/gentelella',
			],
		],
		'form' => [
			'class' => 'ommu\gii\form\Generator',
			'templates' => [
				'default' => '@app/modules/gii/generators/form/default',
			],
		],
		'extension' => [
			'class' => 'ommu\gii\extension\Generator',
			'templates' => [
				'default' => '@app/modules/gii/generators/extension/default',
			],
		],
	]
];
