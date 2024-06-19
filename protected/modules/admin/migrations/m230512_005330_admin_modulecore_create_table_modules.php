<?php
/**
 * m230512_005330_admin_modulecore_create_table_modules
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 12 May 2023, 01:26 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\db\Schema;

class m230512_005330_admin_modulecore_create_table_modules extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_core_modules}}';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_INTEGER . '(11) NOT NULL AUTO_INCREMENT',
				'module_id' => Schema::TYPE_STRING . '(64) NOT NULL',
				'installed' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'0\'',
				'enabled' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'0\'',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'creation_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\' COMMENT \'trigger\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'PRIMARY KEY ([[id]])',
			], $tableOptions);

            $this->createIndex(
                'module_id',
                $tableName,
                'module_id'
            );
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_core_modules}}';
		$this->dropTable($tableName);
	}
}
