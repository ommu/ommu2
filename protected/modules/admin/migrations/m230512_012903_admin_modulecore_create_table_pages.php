<?php
/**
 * m230512_012903_admin_modulecore_create_table_pages
 * 
 * @author Putra Sudaryanto <dwptr@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 12 May 2023, 01:29 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\db\Schema;

class m230512_012903_admin_modulecore_create_table_pages extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_core_pages}}';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'page_id' => Schema::TYPE_SMALLINT . '(5) UNSIGNED NOT NULL AUTO_INCREMENT',
				'publish' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'0\'',
				'name' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL COMMENT \'trigger[delete]\'',
				'desc' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL COMMENT \'trigger[delete],redactor\'',
				'quote' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL COMMENT \'trigger[delete],text\'',
				'media' => Schema::TYPE_TEXT . ' NOT NULL COMMENT \'file\'',
				'media_show' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'0\' COMMENT \'"1=show,0=hide"\'',
				'media_type' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'0\' COMMENT \'"1=large,0=medium"\'',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'creation_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'updated_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\' COMMENT \'trigger\'',
				'slug' => Schema::TYPE_TEXT . ' NOT NULL',
				'PRIMARY KEY ([[page_id]])',
			], $tableOptions);

            $this->createIndex(
                'name',
                $tableName,
                'name'
            );
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_core_pages}}';
		$this->dropTable($tableName);
	}
}
