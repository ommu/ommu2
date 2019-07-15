<?php
/**
 * m190319_120101_rbac_insert_menu
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 18 March 2019, 19:04 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use Yii;
use app\models\Menu;

class m190319_120101_rbac_insert_menu extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_core_auth_item';
		if(Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert('ommu_core_auth_item', ['name', 'type', 'data', 'created_at'], [
				['/#', '2', '', time()],
			]);
		}

		$tableName = Yii::$app->db->tablePrefix . 'ommu_core_menus';
		if(Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert('ommu_core_menus', ['name', 'module', 'icon', 'parent', 'route', 'order', 'data'], [
				['Dashboard', 'rbac', null, null, '/#', null, null],
				['Publications', 'rbac', null, null, '/#', null, null],
				['Settings', 'rbac', null, null, '/#', null, null],
				['Development Tools', 'rbac', null, null, '/#', null, null],
			]);
		}
		
		if(Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert('ommu_core_menus', ['name', 'module', 'icon', 'parent', 'route', 'order', 'data'], [
				['Menu Settings', 'rbac', null, Menu::getParentId('Settings#rbac'), '/rbac/menu/index', null, null],
			]);
		}
	}
}
