<?php
/**
 * m190318_110301_rbac_create_table_ommu_core_auth_item_child
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 18 March 2019, 19:04 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\db\Schema;
use yii\base\InvalidConfigException;
use yii\rbac\DbManager;

class m190318_110301_rbac_create_table_ommu_core_auth_item_child extends \yii\db\Migration
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
		
		$tableName = Yii::$app->db->tablePrefix . $authManager->itemChildTable;
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'parent' => Schema::TYPE_STRING . '(64) NOT NULL',
				'child' => Schema::TYPE_STRING . '(64) NOT NULL',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'PRIMARY KEY ([[parent]], [[child]])',
			], $tableOptions);

			$this->batchInsert($tableName, ['parent', 'child'], [
				['userAdmin', 'userModerator'],
				['userModerator', 'userMember'],
				['userAdmin', '/rbac/*'],
			]);
		}
	}

	public function down()
	{
        $authManager = $this->getAuthManager();
        $this->db = $authManager->db;
        $schema = $this->db->getSchema()->defaultSchema;

		$tableName = Yii::$app->db->tablePrefix . $authManager->itemChildTable;

		$this->dropTable($tableName);
	}
}
