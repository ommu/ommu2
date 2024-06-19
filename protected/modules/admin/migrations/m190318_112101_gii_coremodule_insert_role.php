<?php
/**
 * m190318_112101_gii_coremodule_insert_role
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 18 March 2019, 19:04 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\base\InvalidConfigException;
use yii\rbac\DbManager;

class m190318_112101_gii_coremodule_insert_role extends \yii\db\Migration
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

		$tableName = Yii::$app->db->tablePrefix . $authManager->itemTable;
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['name', 'type', 'data', 'created_at'], [
				['/gii/*', '2', '', time()],
				['/gii/default/index', '2', '', time()],
			]);
		}

		$tableName = Yii::$app->db->tablePrefix . $authManager->itemChildTable;
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['parent', 'child'], [
				['userModerator', '/gii/*'],
			]);
		}
	}
}
