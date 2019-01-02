<?php
/**
 * rbac module config
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 22 December 2017, 03:29 WIB
 * @link https://github.com/ommu/ommu
 *
 */

return [
	'id'             => 'rbac',
	'class'          => app\modules\rbac\Module::className(),
	'core'           => true,
	'bootstrap'      => true,
	'controllerMap'  => [
		'assignment' => [
			'class'          => 'mdm\admin\controllers\AssignmentController',
			'userClassName'  => 'app\modules\user\models\Users',
			'idField'        => 'user_id',
			'usernameField'  => 'email',
			'fullnameField'  => 'displayname',
			'extraColumns'   => [
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
		'menu' => [
			'class' => 'app\modules\rbac\controllers\MenuController',
		],
	],
];