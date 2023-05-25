<?php
/**
 * m230512_005350_admin_modulecore_create_table_zoneCountry
 * 
 * @author Putra Sudaryanto <dwptr@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 12 May 2023, 01:01 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use Yii;
use yii\db\Schema;

class m230512_005350_admin_modulecore_create_table_zoneCountry extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_core_zone_country}}';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'country_id' => Schema::TYPE_SMALLINT . '(5) UNSIGNED NOT NULL AUTO_INCREMENT',
				'country_name' => Schema::TYPE_STRING . '(64) NOT NULL',
				'code' => Schema::TYPE_CHAR . '(2) NOT NULL',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'creation_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'slug' => Schema::TYPE_STRING . '(64) NOT NULL COMMENT \'trigger\'',
				'PRIMARY KEY ([[country_id]])',
			], $tableOptions);

            $this->createIndex(
                'country_name',
                $tableName,
                'country_name'
            );
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_core_zone_country}}';
		$this->dropTable($tableName);
	}
}
