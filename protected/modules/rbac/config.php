<?php
/**
 * rbac module config
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 22 December 2017, 03:29 WIB
 * @link https://github.com/ommu/ommu2
 *
 */

return [
	'id'            => 'rbac',
	'class'         => app\modules\rbac\Module::className(),
	'core'          => true,
	'bootstrap'     => true,
	'controllerMap'  => [
		'assignment' => [
			'class'		  => 'app\modules\rbac\controllers\AssignmentController',
			'userClassName'  => 'app\modules\user\models\Users',
			'idField'		=> 'user_id',
			'usernameField'  => 'email',
			'fullnameField'  => 'displayname',
			'extraColumns'   => [
				[
					'attribute' => 'username',
					'value' => function($model, $key, $index, $column) {
						return $model->username;
					}
				],
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
		'role' => [
			'class' => 'app\modules\rbac\controllers\RoleController',
		],
		'permission' => [
			'class' => 'app\modules\rbac\controllers\PermissionController',
		],
		'route' => [
			'class' => 'app\modules\rbac\controllers\RouteController',
		],
		'rule' => [
			'class' => 'app\modules\rbac\controllers\RuleController',
		],
		'menu' => [
			'class' => 'app\modules\rbac\controllers\MenuController',
		],
	],
];