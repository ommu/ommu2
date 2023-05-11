<?php
/**
 * m230512_012958_admin_modulecore_create_table_pageViews
 * 
 * @author Putra Sudaryanto <dwptr@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 12 May 2023, 01:30 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use Yii;
use yii\db\Schema;

class m230512_012958_admin_modulecore_create_table_pageViews extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_core_page_views}}';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'view_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT',
				'publish' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'page_id' => Schema::TYPE_SMALLINT . '(5) UNSIGNED NOT NULL',
				'user_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'views' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT \'1\'',
				'view_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'view_ip' => Schema::TYPE_STRING . '(20) NOT NULL',
				'deleted_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\' COMMENT \'trigger\'',
				'PRIMARY KEY ([[view_id]])',
				'CONSTRAINT ommu_core_page_views_ibfk_1 FOREIGN KEY ([[page_id]]) REFERENCES {{%ommu_core_pages}} ([[page_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
				'CONSTRAINT ommu_core_page_views_ibfk_2 FOREIGN KEY ([[user_id]]) REFERENCES {{%ommu_users}} ([[user_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_core_page_views}}';
		$this->dropTable($tableName);
	}
}
