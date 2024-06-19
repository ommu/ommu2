<?php
/**
 * m230512_012838_admin_modulecore_create_table_meta
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 12 May 2023, 01:28 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\db\Schema;

class m230512_012838_admin_modulecore_create_table_meta extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_core_meta}}';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_TINYINT . '(1) UNSIGNED NOT NULL AUTO_INCREMENT',
				'meta_image' => Schema::TYPE_TEXT . ' NOT NULL COMMENT \'file\'',
				'meta_image_alt' => Schema::TYPE_TEXT . ' NOT NULL',
				'office_on' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\' COMMENT \'"0=disable, 1=enable"\'',
				'office_name' => Schema::TYPE_STRING . '(64) NOT NULL COMMENT \'drop\'',
				'office_location' => Schema::TYPE_STRING . '(32) NOT NULL COMMENT \'drop\'',
				'office_place' => Schema::TYPE_TEXT . ' NOT NULL COMMENT \'drop\'',
				'office_country_id' => Schema::TYPE_SMALLINT . '(5) UNSIGNED COMMENT \'drop\'',
				'office_province_id' => Schema::TYPE_SMALLINT . '(5) UNSIGNED COMMENT \'drop\'',
				'office_city_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED COMMENT \'drop\'',
				'office_district' => Schema::TYPE_STRING . '(64) NOT NULL COMMENT \'drop\'',
				'office_village' => Schema::TYPE_STRING . '(64) NOT NULL COMMENT \'drop\'',
				'office_zipcode' => Schema::TYPE_CHAR . '(5) NOT NULL COMMENT \'drop\'',
				'office_hour' => Schema::TYPE_TEXT . ' NOT NULL',
				'office_phone' => Schema::TYPE_STRING . '(32) NOT NULL COMMENT \'drop\'',
				'office_fax' => Schema::TYPE_STRING . '(32) NOT NULL COMMENT \'drop\'',
				'office_email' => Schema::TYPE_STRING . '(32) NOT NULL COMMENT \'drop\'',
				'office_hotline' => Schema::TYPE_STRING . '(32) NOT NULL COMMENT \'drop\'',
				'office_website' => Schema::TYPE_STRING . '(32) NOT NULL COMMENT \'drop\'',
				'map_icons' => Schema::TYPE_STRING . '(32) NOT NULL',
				'map_icon_size' => Schema::TYPE_TEXT . ' NOT NULL',
				'google_on' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\' COMMENT \'drop\'',
				'twitter_on' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\' COMMENT \'drop\'',
				'twitter_card' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\' COMMENT \'"1=summary, 2=summary_large_image, 3=photo, 4=app"\'',
				'twitter_site' => Schema::TYPE_STRING . '(32) NOT NULL',
				'twitter_creator' => Schema::TYPE_STRING . '(32) NOT NULL',
				'twitter_photo_size' => Schema::TYPE_TEXT . ' NOT NULL',
				'twitter_country' => Schema::TYPE_STRING . '(32) NOT NULL',
				'twitter_iphone' => Schema::TYPE_TEXT . ' NOT NULL',
				'twitter_ipad' => Schema::TYPE_TEXT . ' NOT NULL',
				'twitter_googleplay' => Schema::TYPE_TEXT . ' NOT NULL',
				'facebook_on' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\' COMMENT \'drop\'',
				'facebook_type' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\' COMMENT \'"1=profile, 2=website"\'',
				'facebook_profile_firstname' => Schema::TYPE_STRING . '(32) NOT NULL',
				'facebook_profile_lastname' => Schema::TYPE_STRING . '(32) NOT NULL',
				'facebook_profile_username' => Schema::TYPE_STRING . '(32) NOT NULL',
				'facebook_sitename' => Schema::TYPE_STRING . '(64) NOT NULL',
				'facebook_see_also' => Schema::TYPE_STRING . '(256) NOT NULL',
				'facebook_admins' => Schema::TYPE_STRING . '(32) NOT NULL',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'PRIMARY KEY ([[id]])',
				'CONSTRAINT ommu_core_meta_ibfk_1 FOREIGN KEY ([[office_country_id]]) REFERENCES {{%ommu_core_zone_country}} ([[country_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
				'CONSTRAINT ommu_core_meta_ibfk_2 FOREIGN KEY ([[office_province_id]]) REFERENCES {{%ommu_core_zone_province}} ([[province_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
				'CONSTRAINT ommu_core_meta_ibfk_3 FOREIGN KEY ([[office_city_id]]) REFERENCES {{%ommu_core_zone_city}} ([[city_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_core_meta}}';
		$this->dropTable($tableName);
	}
}
