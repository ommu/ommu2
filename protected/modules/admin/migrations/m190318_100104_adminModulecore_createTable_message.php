<?php
/**
 * m190318_100104_adminModulecore_createTable_message
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 3 June 2023, 18:08 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\db\Schema;

class m190318_100104_adminModulecore_createTable_message extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'message';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
				'language' => Schema::TYPE_STRING . '(16) NOT NULL',
				'translation' => Schema::TYPE_TEXT,
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'creation_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'PRIMARY KEY ([[id]], [[language]])',
				'CONSTRAINT message_ibfk_1 FOREIGN KEY ([[id]]) REFERENCES source_message ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);

            $this->createIndex(
                'language',
                $tableName,
                'language'
            );
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'message';
		$this->dropTable($tableName);
	}
}
