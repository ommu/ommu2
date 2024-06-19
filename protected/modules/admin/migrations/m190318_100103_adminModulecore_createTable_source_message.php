<?php
/**
 * m190318_100103_adminModulecore_createTable_source_message
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 3 June 2023, 18:09 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\db\Schema;

class m190318_100103_adminModulecore_createTable_source_message extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'source_message';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_INTEGER . '(11) NOT NULL AUTO_INCREMENT',
				'category' => Schema::TYPE_STRING . '(255)',
				'message' => Schema::TYPE_TEXT,
				'location' => Schema::TYPE_TEXT,
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'creation_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'_id' => Schema::TYPE_INTEGER . '(11)',
				'PRIMARY KEY ([[id]])',
			], $tableOptions);

            $this->createIndex(
                'category',
                $tableName,
                'category'
            );
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'source_message';
		$this->dropTable($tableName);
	}
}
