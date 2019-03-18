<?php
/**
 * m190318_120101_ommu_core_auth_item
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 18 March 2019, 19:02 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use Yii;
use yii\db\Schema;

class m190318_120101_ommu_core_auth_item extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		
		$tableName = Yii::$app->db->tablePrefix . 'ommu_core_auth_item';
		if(!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable('ommu_core_auth_item', [
				'name' => Schema::TYPE_STRING . '(64) NOT NULL',
				'type' => Schema::TYPE_INTEGER . '(11) NOT NULL',
				'description' => Schema::TYPE_TEXT,
				'rule_name' => Schema::TYPE_STRING . '(64)',
				'data' => Schema::TYPE_TEXT,
				'created_at' => Schema::TYPE_INTEGER . '(11)',
				'updated_at' => Schema::TYPE_INTEGER . '(11)',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'PRIMARY KEY ([[name]])',
			], $tableOptions);

			$this->batchInsert('ommu_core_auth_item', ['name', 'type', 'data', 'created_at'], [
				['userAdmin', '1', '', time()],
				['userModerator', '1', '', time()],
				['userMember', '1', '', time()],
				['/rbac/*', '2', '', time()],
			]);
		}
	}

	public function down()
	{
		$this->dropTable('ommu_core_auth_item');
	}
}
