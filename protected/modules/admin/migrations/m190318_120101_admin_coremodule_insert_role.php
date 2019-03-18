<?php
/**
 * m190318_120101_admin_coremodule_insert_role
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 18 March 2019, 19:04 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use Yii;
use yii\db\Schema;

class m190318_120101_admin_coremodule_insert_role extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_core_auth_item';
		if(Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert('ommu_core_auth_item', ['name', 'type', 'data', 'created_at'], [
				['/admin/dashboard/*', '2', '', time()],
				['/admin/dashboard/index', '2', '', time()],
				['/admin/module/*', '2', '', time()],
				['/admin/module/index', '2', '', time()],
				['/admin/page/admin/*', '2', '', time()],
				['/admin/page/admin/index', '2', '', time()],
				['/admin/page/view/*', '2', '', time()],
				['/admin/page/view-detail/*', '2', '', time()],
				['/admin/tag/*', '2', '', time()],
				['/admin/zone/country/*', '2', '', time()],
				['/admin/zone/province/*', '2', '', time()],
				['/admin/zone/city/*', '2', '', time()],
				['/admin/zone/district/*', '2', '', time()],
				['/admin/zone/village/*', '2', '', time()],
				['/admin/language/*', '2', '', time()],
				['/admin/setting/general', '2', '', time()],
				['/admin/setting/banned', '2', '', time()],
				['/admin/setting/signup', '2', '', time()],
				['/admin/setting/language', '2', '', time()],
				['/admin/setting/analytic', '2', '', time()],
			]);
		}

		$tableName = Yii::$app->db->tablePrefix . 'ommu_core_auth_item_child';
		if(Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert('ommu_core_auth_item_child', ['parent', 'child'], [
				['userAdmin', '/admin/module/*'],
				['userModerator', '/admin/dashboard/*'],
				['userModerator', '/admin/page/admin/*'],
				['userModerator', '/admin/page/view/*'],
				['userModerator', '/admin/page/view-detail/*'],
				['userModerator', '/admin/tag/*'],
				['userModerator', '/admin/zone/country/*'],
				['userModerator', '/admin/zone/province/*'],
				['userModerator', '/admin/zone/city/*'],
				['userModerator', '/admin/zone/district/*'],
				['userModerator', '/admin/zone/village/*'],
				['userModerator', '/admin/language/*'],
				['userModerator', '/admin/setting/general'],
				['userModerator', '/admin/setting/banned'],
				['userModerator', '/admin/setting/signup'],
				['userModerator', '/admin/setting/language'],
				['userModerator', '/admin/setting/analytic'],
			]);
		}
	}
}
