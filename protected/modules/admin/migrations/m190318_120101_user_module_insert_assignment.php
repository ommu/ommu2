<?php
/**
 * m190318_120101_user_module_insert_assignment
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

class m190318_120101_user_module_insert_assignment extends \yii\db\Migration
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

		$tableName = Yii::$app->db->tablePrefix . $authManager->assignmentTable;
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableNam, ['item_name', 'user_id', 'created_at'], [
				['userAdmin', '1', time()],
				['userAdmin', '2', time()],
				['userModerator', '3', time()],
				['userModerator', '4', time()],
			]);
		}
	}
}
