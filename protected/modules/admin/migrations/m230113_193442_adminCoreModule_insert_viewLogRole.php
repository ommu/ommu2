<?php
/**
 * m230113_193442_adminCoreModule_insert_viewLogRole
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 13 January 2023, 19:35 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\base\InvalidConfigException;
use yii\rbac\DbManager;

class m230113_193442_adminCoreModule_insert_viewLogRole extends \yii\db\Migration
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
				['/admin/view-log/*', '2', '', time()],
			]);
		}

		$tableName = Yii::$app->db->tablePrefix . $authManager->itemChildTable;
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['parent', 'child'], [
				['userAdmin', '/admin/view-log/*'],
			]);
		}
	}
}
