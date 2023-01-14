<?php
/**
 * admin module config
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
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
		'symlink' => 'app\modules\admin\controllers\SymlinkController',
		'composer' => 'app\modules\admin\controllers\ComposerController',
		'migrate' => 'app\modules\admin\controllers\MigrateController',
		'view-log' => 'app\modules\admin\controllers\ViewLogController',
		'asset' => 'app\modules\admin\controllers\AssetController',
	],
];