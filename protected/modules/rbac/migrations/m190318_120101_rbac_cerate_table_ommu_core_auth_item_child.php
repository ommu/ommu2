<?php
/**
 * m190318_120101_rbac_cerate_table_ommu_core_auth_item_child
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 18 March 2019, 19:04 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use Yii;
use yii\db\Schema;

class m190318_120101_rbac_cerate_table_ommu_core_auth_item_child extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		
		$tableName = Yii::$app->db->tablePrefix . 'ommu_core_auth_item_child';
		if(!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable('ommu_core_auth_item_child', [
				'parent' => Schema::TYPE_STRING . '(64) NOT NULL',
				'child' => Schema::TYPE_STRING . '(64) NOT NULL',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'PRIMARY KEY ([[parent]], [[child]])',
			], $tableOptions);

			$this->batchInsert('ommu_core_auth_item_child', ['parent', 'child'], [
				['userAdmin', 'userModerator'],
				['userModerator', 'userMember'],
				['userModerator', '/rbac/*'],
			]);
		}
	}

	public function down()
	{
		$this->dropTable('ommu_core_auth_item_child');
	}
}
