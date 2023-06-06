<?php
/**
 * m190318_110501_rbac_create_table_ommu_core_menus
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 19 March 2019, 10:08 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\db\Schema;
use mdm\admin\components\Configs;

class m190318_110501_rbac_create_table_ommu_core_menus extends \yii\db\Migration
{
	public function up()
	{
        $menuTable = Configs::instance()->menuTable;

		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		
		$tableName = Yii::$app->db->tablePrefix . $menuTable;
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
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
				$tableName,
				'parent'
			);

			$this->createIndex(
				'route',
				$tableName,
				'route'
			);

			$this->createIndex(
				'name',
				$tableName,
				['name', 'module']
			);
		}
	}

	public function down()
	{
        $menuTable = Configs::instance()->menuTable;
		$tableName = Yii::$app->db->tablePrefix . $menuTable;

		$this->dropTable($tableName);
	}
}
