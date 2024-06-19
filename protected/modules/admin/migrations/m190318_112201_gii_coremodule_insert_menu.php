<?php
/**
 * m190318_112201_gii_coremodule_insert_menu
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 18 March 2019, 19:04 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use app\models\Menu;
use mdm\admin\components\Configs;

class m190318_112201_gii_coremodule_insert_menu extends \yii\db\Migration
{
	public function up()
	{
        $menuTable = Configs::instance()->menuTable;
		$tableName = Yii::$app->db->tablePrefix . $menuTable;
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['name', 'module', 'icon', 'parent', 'route', 'order', 'data'], [
				['Gii Ganerator', 'gii', null, Menu::getParentId('Development Tools#rbac'), '/gii/default/index', null, null],
			]);
		}
	}
}
