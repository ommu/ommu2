<?php return [
	'name' => 'Br0t0',
	'language' => 'id',
	'components' => [
		'mailer' => [
			'class' => 'yii\swiftmailer\Mailer',
			'transport' => [
				'class' => 'Swift_SmtpTransport',
				'host' => 'mail.ommu.co',
				'username' => 'noreply@ommu.co',
				'password' => '2jmFUcX8MXSR',
				'port' => '465',
				'encryption' => 'ssl',
			],
		],
	],
	'modules' => [
		'rbac' => [
			'class' => 'mdm\admin\Module',
			// 'class' => 'app\coremodules\rbac\Module',
			'controllerMap' => [
				'assignment' => [
					'class' => 'mdm\admin\controllers\AssignmentController',
					'userClassName' => 'app\modules\user\models\Users',
					'idField' => 'user_id',
					'usernameField' => 'email',
					'fullnameField' => 'displayname',
					'extraColumns' => [
						[
							'attribute' => 'displayname',
							'value' => function($model, $key, $index, $column) {
								return $model->displayname;
							}
						],
						[
							'attribute' => 'level_id',
							'value' => function($model, $key, $index, $column) {
								return isset($model->level) ? $model->level->name_i : '-';
							},
							'filter' => [
								1 => 'Administrators',
								2 => 'Moderator',
								3 => 'Members',
							],
						],
					],
					'searchClass' => 'ommu\users\models\search\Users',
				],
			],
		],
		'gii' => [
			'class' => 'app\modules\gii\Module',
			'allowedIPs' => ['127.0.0.1', '::1', '192.168.3.13', '192.168.2.13'],
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
						'default' => '@app/libraries/gii/form/default',
					],
				],
				'extension' => [
					'class' => 'ommu\gii\extension\Generator',
					'templates' => [
						'default' => '@app/libraries/gii/extension/default',
					],
				],
			],
		],
	],
]; ?>
