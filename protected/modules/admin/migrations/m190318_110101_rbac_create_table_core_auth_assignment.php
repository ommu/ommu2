<?php
/**
 * m190318_110101_rbac_create_table_core_auth_assignment
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 18 March 2019, 19:05 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\db\Schema;
use yii\base\InvalidConfigException;
use yii\rbac\DbManager;

class m190318_110101_rbac_create_table_core_auth_assignment extends \yii\db\Migration
{
    /**
     * @throws yii\base\InvalidConfigException
     * @return DbManager
     */
    protected function getAuthManager()
    {
        $authManager = Yii::$app->getAuthManager();
        if (!$authManager instanceof DbManager) {
            throw new InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
        }

        return $authManager;
    }

	public function up()
	{
        $authManager = $this->getAuthManager();
        $this->db = $authManager->db;
        $schema = $this->db->getSchema()->defaultSchema;

		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		
		$tableName = Yii::$app->db->tablePrefix . $authManager->assignmentTable;
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'item_name' => Schema::TYPE_STRING . '(64) NOT NULL',
				'user_id' => Schema::TYPE_STRING . '(64) NOT NULL',
				'created_at' => Schema::TYPE_INTEGER . '(11)',
				'PRIMARY KEY ([[item_name]], [[user_id]])',
			], $tableOptions);
		}
	}

	public function down()
	{
        $authManager = $this->getAuthManager();
        $this->db = $authManager->db;
        $schema = $this->db->getSchema()->defaultSchema;

		$tableName = Yii::$app->db->tablePrefix . $authManager->assignmentTable;

		$this->dropTable($tableName);
	}
}
