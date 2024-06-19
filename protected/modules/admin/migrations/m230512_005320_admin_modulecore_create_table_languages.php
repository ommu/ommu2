<?php
/**
 * m230512_005320_admin_modulecore_create_table_languages
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 12 May 2023, 01:27 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\db\Schema;

class m230512_005320_admin_modulecore_create_table_languages extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_core_languages}}';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'language_id' => Schema::TYPE_SMALLINT . '(5) UNSIGNED NOT NULL AUTO_INCREMENT',
				'actived' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'0\' COMMENT \'Enable,Disable\'',
				'default' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'0\' COMMENT \'Default,No Default\'',
				'code' => Schema::TYPE_STRING . '(6) NOT NULL',
				'name' => Schema::TYPE_STRING . '(32) NOT NULL',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'creation_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'updated_date' => Schema::TYPE_DATETIME . ' DEFAULT \'0000-00-00 00:00:00\' COMMENT \'trigger\'',
				'PRIMARY KEY ([[language_id]])',
			], $tableOptions);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_core_languages}}';
		$this->dropTable($tableName);
	}
}
