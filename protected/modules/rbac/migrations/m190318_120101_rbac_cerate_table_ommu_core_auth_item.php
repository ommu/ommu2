<?php
/**
 * m190318_120101_rbac_cerate_table_ommu_core_auth_item
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 18 March 2019, 19:02 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use Yii;
use yii\db\Schema;
use yii\base\InvalidConfigException;
use yii\rbac\DbManager;

class m190318_120101_rbac_cerate_table_ommu_core_auth_item extends \yii\db\Migration
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
		
		$tableName = Yii::$app->db->tablePrefix . $authManager->itemTable;
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
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

			$this->batchInsert($tableName, ['name', 'type', 'data', 'created_at'], [
				['userAdmin', '1', '', time()],
				['userModerator', '1', '', time()],
				['userMember', '1', '', time()],
				['/rbac/*', '2', '', time()],
				['/rbac/assignment/index', '2', '', time()],
				['/rbac/menu/index', '2', '', time()],
			]);
		}
	}

	public function down()
	{
        $authManager = $this->getAuthManager();
        $this->db = $authManager->db;
        $schema = $this->db->getSchema()->defaultSchema;

		$tableName = Yii::$app->db->tablePrefix . $authManager->itemTable;

		$this->dropTable($tableName);
	}
}
