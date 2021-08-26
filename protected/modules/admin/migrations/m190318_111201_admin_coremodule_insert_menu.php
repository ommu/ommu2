<?php
/**
 * m190318_111201_admin_coremodule_insert_menu
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 18 March 2019, 19:04 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use Yii;
use mdm\admin\components\Configs;
use app\models\Menu;

class m190318_111201_admin_coremodule_insert_menu extends \yii\db\Migration
{
	public function up()
	{
        $menuTable = Configs::instance()->menuTable;
		$tableName = Yii::$app->db->tablePrefix . $menuTable;

		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['name', 'module', 'icon', 'parent', 'route', 'order', 'data'], [
				['Summary', 'admin', null, Menu::getParentId('Dashboard#rbac'), '/admin/dashboard/index', null, null],
				['Static Pages', 'admin', null, Menu::getParentId('Publications#rbac'), '/admin/page/admin/index', null, null],
				['Public Permissions', 'admin', null, Menu::getParentId('Settingss#rbac'), '/admin/setting/general', null, null],
				['Spam & Banning Tools', 'admin', null, Menu::getParentId('Settingss#rbac'), '/admin/setting/banned', null, null],
				['Signup Settings', 'admin', null, Menu::getParentId('Settingss#rbac'), '/admin/setting/signup', null, null],
				['Language Settings', 'admin', null, Menu::getParentId('Settingss#rbac'), '/admin/setting/language', null, null],
				['Module Manager', 'admin', null, Menu::getParentId('Development Tools#rbac'), '/admin/module/index', null, null],
				['Phrases', 'admin', null, Menu::getParentId('Development Tools#rbac'), '/phrase/index', null, null],
			]);
		}
	}
}
