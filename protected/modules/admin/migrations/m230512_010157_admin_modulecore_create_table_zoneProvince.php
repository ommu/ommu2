<?php
/**
 * m230512_010157_admin_modulecore_create_table_zoneProvince
 * 
 * @author Putra Sudaryanto <dwptr@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 12 May 2023, 01:02 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\db\Schema;

class m230512_010157_admin_modulecore_create_table_zoneProvince extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_core_zone_province}}';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'province_id' => Schema::TYPE_SMALLINT . '(5) UNSIGNED NOT NULL AUTO_INCREMENT',
				'publish' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'country_id' => Schema::TYPE_SMALLINT . '(5) UNSIGNED DEFAULT \'72\'',
				'province_name' => Schema::TYPE_STRING . '(64) NOT NULL',
				'mfdonline' => Schema::TYPE_CHAR . '(2) NOT NULL',
				'checked' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'0\'',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'creation_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'updated_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\' COMMENT \'trigger\'',
				'slug' => Schema::TYPE_STRING . '(64) NOT NULL COMMENT \'trigger\'',
				'PRIMARY KEY ([[province_id]])',
				'CONSTRAINT ommu_core_zone_province_ibfk_1 FOREIGN KEY ([[country_id]]) REFERENCES {{%ommu_core_zone_country}} ([[country_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);

            $this->createIndex(
                'province_name',
                $tableName,
                'province_name'
            );

            $this->createIndex(
                'mfdonline',
                $tableName,
                'mfdonline'
            );
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_core_zone_province}}';
		$this->dropTable($tableName);
	}
}
