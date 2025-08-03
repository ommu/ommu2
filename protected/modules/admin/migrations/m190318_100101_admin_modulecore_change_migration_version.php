<?php
/**
 * m190318_100101_admin_modulecore_change_migration_version
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 30 August 2021, 06:59 WIB
 * @link https://github.com/ommu/ommu2
 *
 */

class m190318_100101_admin_modulecore_change_migration_version extends \yii\db\Migration
{
	/**
	 * {@inheritdoc}
	 */
    protected function getMigrationTable()
    {
        return \app\commands\MigrateController::getMigrationTable();
    }

	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . $this->getMigrationTable();
		if (Yii::$app->db->getTableSchema($tableName, true)) {
            $this->update($tableName, ['version' => 'm190318_110101_rbac_create_table_core_auth_assignment'], ['version' => 'm190318_120101_rbac_create_table_core_auth_assignment']);
            $this->update($tableName, ['version' => 'm190318_110201_rbac_create_table_ommu_core_auth_item'], ['version' => 'm190318_120101_rbac_create_table_ommu_core_auth_item']);
            $this->update($tableName, ['version' => 'm190318_110301_rbac_create_table_ommu_core_auth_item_child'], ['version' => 'm190318_120101_rbac_create_table_ommu_core_auth_item_child']);
            $this->update($tableName, ['version' => 'm190318_110401_rbac_create_table_ommu_core_auth_rule'], ['version' => 'm190318_120101_rbac_create_table_ommu_core_auth_rule']);
            $this->update($tableName, ['version' => 'm190318_110501_rbac_create_table_ommu_core_menus'], ['version' => 'm190319_030101_rbac_create_table_ommu_core_menus']);
            $this->update($tableName, ['version' => 'm190318_110601_rbac_insert_menu'], ['version' => 'm190319_120101_rbac_insert_menu']);
            $this->update($tableName, ['version' => 'm190318_111101_admin_coremodule_insert_role'], ['version' => 'm190318_120101_admin_coremodule_insert_role']);
            $this->update($tableName, ['version' => 'm190318_111201_admin_coremodule_insert_menu'], ['version' => 'm190319_120101_admin_coremodule_insert_menu']);
            $this->update($tableName, ['version' => 'm190318_112101_gii_coremodule_insert_role'], ['version' => 'm190318_120101_gii_insert_role']);
            $this->update($tableName, ['version' => 'm190318_112201_gii_coremodule_insert_menu'], ['version' => 'm190319_120101_gii_insert_menu']);
            $this->update($tableName, ['version' => 'm190318_120101_user_module_insert_assignment'], ['version' => 'm190318_120101_user_coremodule_insert_assignment']);
            $this->update($tableName, ['version' => 'm191116_175300_user_module_insert_changePassword_role'], ['version' => 'm191116_175300_user_coremodule_insert_role']);
		}
	}
}
