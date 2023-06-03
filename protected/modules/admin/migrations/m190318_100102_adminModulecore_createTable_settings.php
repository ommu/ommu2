<?php
/**
 * m190318_100102_adminModulecore_createTable_settings
 * 
 * @author Putra Sudaryanto <dwptr@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 3 June 2023, 18:07 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use Yii;
use yii\db\Schema;

class m190318_100102_adminModulecore_createTable_settings extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_settings';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'name' => Schema::TYPE_STRING . '(64) NOT NULL',
				'value' => Schema::TYPE_TEXT . ' NOT NULL',
				'module_id' => Schema::TYPE_STRING . '(64) NOT NULL',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'creation_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'PRIMARY KEY ([[name]])',
			], $tableOptions);

			$this->createIndex(
				'nameWithModuleId',
				$tableName,
				['name', 'module_id']
			);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_settings';
		$this->dropTable($tableName);
	}
}
