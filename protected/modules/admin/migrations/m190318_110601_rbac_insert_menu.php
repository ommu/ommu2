<?php
/**
 * m190318_110601_rbac_insert_menu
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 18 March 2019, 19:04 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use app\models\Menu;
use yii\base\InvalidConfigException;
use yii\rbac\DbManager;
use mdm\admin\components\Configs;

class m190318_110601_rbac_insert_menu extends \yii\db\Migration
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
				['/#', '2', '', time()],
			]);
		}

        $menuTable = Configs::instance()->menuTable;
		$tableName = Yii::$app->db->tablePrefix . $menuTable;
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($menuTable, ['name', 'module', 'icon', 'parent', 'route', 'order', 'data'], [
				['Dashboard', 'rbac', null, null, '/#', null, null],
				['Publications', 'rbac', null, null, '/#', null, null],
				['Settings', 'rbac', null, null, '/#', null, null],
				['Development Tools', 'rbac', null, null, '/#', null, null],
			]);

			$this->batchInsert($menuTable, ['name', 'module', 'icon', 'parent', 'route', 'order', 'data'], [
				['Menu Settings', 'rbac', null, Menu::getParentId('Settings#rbac'), '/rbac/menu/index', null, null],
			]);
		}
	}
}
