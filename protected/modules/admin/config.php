<?php
/**
 * admin module config
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 22 December 2017, 03:29 WIB
 * @link https://github.com/ommu/ommu
 *
 */

return [
	'id'     => 'admin',
	'class'  => app\modules\admin\Module::className(),
	'core'   => true,

	'controllerMap' => [
		'dashboard' => 'app\modules\admin\controllers\DashboardController',
		'login' => 'app\modules\admin\controllers\LoginController',
	],
];