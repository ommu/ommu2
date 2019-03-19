<?php
/**
 * m190319_030101_rbac_create_table_ommu_core_menus
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 19 March 2019, 10:08 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use Yii;
use yii\db\Schema;

class m190319_030101_rbac_create_table_ommu_core_menus extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		
		$tableName = Yii::$app->db->tablePrefix . 'ommu_core_menus';
		if(!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable('ommu_core_menus', [
				'id' => Schema::TYPE_INTEGER . '(11) NOT NULL AUTO_INCREMENT',
				'cat_id' => Schema::TYPE_SMALLINT . '(5) UNSIGNED',
				'name' => Schema::TYPE_STRING . '(128) NOT NULL',
				'icon' => Schema::TYPE_STRING . '(64)',
				'parent' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'route' => Schema::TYPE_STRING . '(256)',
				'order' => Schema::TYPE_INTEGER . '(11)',
				'data' => Schema::TYPE_TEXT,
				'creation_date' => Schema::TYPE_TIMESTAMP . ' DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'creation_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'PRIMARY KEY ([[id]])',
				'FOREIGN KEY ([[cat_id]]) REFERENCES ommu_core_menu_category ([[id]]) ON DELETE SET NULL ON UPDATE CASCADE',
			], $tableOptions);
		}
	}

	public function down()
	{
		$this->dropTable('ommu_core_menus');
	}
}
