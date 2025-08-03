<?php
/**
 * m230512_010239_admin_modulecore_create_table_zoneCity
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 12 May 2023, 01:02 WIB
 * @link https://github.com/ommu/ommu2
 *
 */

use yii\db\Schema;

class m230512_010239_admin_modulecore_create_table_zoneCity extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_core_zone_city}}';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'city_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT',
				'publish' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'province_id' => Schema::TYPE_SMALLINT . '(5) UNSIGNED',
				'city_name' => Schema::TYPE_STRING . '(64) NOT NULL',
				'mfdonline' => Schema::TYPE_CHAR . '(4) NOT NULL',
				'checked' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'0\'',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'creation_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'updated_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\' COMMENT \'trigger\'',
				'slug' => Schema::TYPE_STRING . '(64) NOT NULL COMMENT \'trigger\'',
				'PRIMARY KEY ([[city_id]])',
				'CONSTRAINT ommu_core_zone_city_ibfk_1 FOREIGN KEY ([[province_id]]) REFERENCES {{%ommu_core_zone_province}} ([[province_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);

            $this->createIndex(
                'city_name',
                $tableName,
                'city_name'
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
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_core_zone_city}}';
		$this->dropTable($tableName);
	}
}
