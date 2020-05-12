<?php
/**
 * m190319_030101_rbac_create_table_ommu_core_menus
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
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
				'name' => Schema::TYPE_STRING . '(128) NOT NULL',
				'parent' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'route' => Schema::TYPE_STRING . '(256)',
				'order' => Schema::TYPE_INTEGER . '(11)',
				'data' => Schema::TYPE_TEXT,
				'public' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'0\'',
				'module' => Schema::TYPE_STRING . '(32) NOT NULL',
				'icon' => Schema::TYPE_STRING . '(64)',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'creation_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'PRIMARY KEY ([[id]])',
			], $tableOptions);

			$this->createIndex(
				'parent',
				'ommu_core_menus',
				'parent'
			);

			$this->createIndex(
				'route',
				'ommu_core_menus',
				'route'
			);

			$this->createIndex(
				'name',
				'ommu_core_menus',
				['name', 'module']
			);
		}
	}

	public function down()
	{
		$this->dropIndex(
			'parent',
			'ommu_core_menus'
		);

		$this->dropIndex(
			'route',
			'ommu_core_menus'
		);

		$this->dropIndex(
			'name',
			'ommu_core_menus'
		);

		$this->dropTable('ommu_core_menus');
	}
}
