<?php
/**
 * m230512_010311_admin_modulecore_create_table_zoneDistrict
 * 
 * @author Putra Sudaryanto <dwptr@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 12 May 2023, 01:03 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\db\Schema;

class m230512_010311_admin_modulecore_create_table_zoneDistrict extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_core_zone_district}}';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'district_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT',
				'publish' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'city_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'district_name' => Schema::TYPE_STRING . '(64) NOT NULL',
				'mfdonline' => Schema::TYPE_CHAR . '(7) NOT NULL',
				'checked' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'0\'',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'creation_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'updated_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\' COMMENT \'trigger\'',
				'slug' => Schema::TYPE_STRING . '(64) NOT NULL COMMENT \'trigger\'',
				'PRIMARY KEY ([[district_id]])',
				'CONSTRAINT ommu_core_zone_district_ibfk_1 FOREIGN KEY ([[city_id]]) REFERENCES {{%ommu_core_zone_city}} ([[city_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);

            $this->createIndex(
                'district_name',
                $tableName,
                'district_name'
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
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_core_zone_district}}';
		$this->dropTable($tableName);
	}
}
