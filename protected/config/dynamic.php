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
		],
	],
]; ?>
