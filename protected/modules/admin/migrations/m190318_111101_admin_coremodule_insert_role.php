<?php
/**
 * m190318_111101_admin_coremodule_insert_role
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 18 March 2019, 19:04 WIB
 * @link https://github.com/ommu/ommu2
 *
 */

use yii\base\InvalidConfigException;
use yii\rbac\DbManager;

class m190318_111101_admin_coremodule_insert_role extends \yii\db\Migration
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
				['/admin/dashboard/*', '2', '', time()],
				['/admin/dashboard/index', '2', '', time()],
				['/admin/module/*', '2', '', time()],
				['/admin/module/index', '2', '', time()],
				['/admin/page/admin/*', '2', '', time()],
				['/admin/page/admin/index', '2', '', time()],
				['/admin/page/view/*', '2', '', time()],
				['/admin/page/view-detail/*', '2', '', time()],
				['/admin/tag/*', '2', '', time()],
				['/admin/zone/country/*', '2', '', time()],
				['/admin/zone/province/*', '2', '', time()],
				['/admin/zone/city/*', '2', '', time()],
				['/admin/zone/district/*', '2', '', time()],
				['/admin/zone/village/*', '2', '', time()],
				['/admin/language/*', '2', '', time()],
				['/admin/setting/general', '2', '', time()],
				['/admin/setting/banned', '2', '', time()],
				['/admin/setting/signup', '2', '', time()],
				['/admin/setting/language', '2', '', time()],
				['/phrase/*', '2', '', time()],
				['/phrase/index', '2', '', time()],
			]);
		}

		$tableName = Yii::$app->db->tablePrefix . $authManager->itemChildTable;
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['parent', 'child'], [
				['userAdmin', '/admin/module/*'],
				['userModerator', '/admin/dashboard/*'],
				['userModerator', '/admin/page/admin/*'],
				['userModerator', '/admin/page/view/*'],
				['userModerator', '/admin/page/view-detail/*'],
				['userModerator', '/admin/tag/*'],
				['userModerator', '/admin/zone/country/*'],
				['userModerator', '/admin/zone/province/*'],
				['userModerator', '/admin/zone/city/*'],
				['userModerator', '/admin/zone/district/*'],
				['userModerator', '/admin/zone/village/*'],
				['userModerator', '/admin/language/*'],
				['userModerator', '/admin/setting/general'],
				['userModerator', '/admin/setting/banned'],
				['userModerator', '/admin/setting/signup'],
				['userModerator', '/admin/setting/language'],
				['userModerator', '/phrase/*'],
			]);
		}
	}
}
