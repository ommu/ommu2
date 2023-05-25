<?php
/**
 * m230512_010725_admin_modulecore_create_table_zoneVillage
 * 
 * @author Putra Sudaryanto <dwptr@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 12 May 2023, 01:07 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use Yii;
use yii\db\Schema;

class m230512_010725_admin_modulecore_create_table_zoneVillage extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_core_zone_village}}';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'village_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT',
				'publish' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'district_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'village_name' => Schema::TYPE_STRING . '(64) NOT NULL',
				'zipcode' => Schema::TYPE_CHAR . '(5) NOT NULL',
				'mfdonline' => Schema::TYPE_CHAR . '(10) NOT NULL',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'creation_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'updated_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\' COMMENT \'trigger\'',
				'slug' => Schema::TYPE_STRING . '(64) NOT NULL COMMENT \'trigger\'',
				'PRIMARY KEY ([[village_id]])',
				'CONSTRAINT ommu_core_zone_village_ibfk_1 FOREIGN KEY ([[district_id]]) REFERENCES {{%ommu_core_zone_district}} ([[district_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);

            $this->createIndex(
                'village_name',
                $tableName,
                'village_name'
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
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_core_zone_village}}';
		$this->dropTable($tableName);
	}
}
