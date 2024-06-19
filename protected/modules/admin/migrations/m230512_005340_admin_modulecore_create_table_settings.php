<?php
/**
 * m230512_005340_admin_modulecore_create_table_settings
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 12 May 2023, 01:25 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\db\Schema;

class m230512_005340_admin_modulecore_create_table_settings extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_core_settings}}';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_TINYINT . '(1) UNSIGNED NOT NULL AUTO_INCREMENT',
				'online' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\' COMMENT \'drop\'',
				'site_oauth' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'0\' COMMENT \'drop\'',
				'site_type' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\' COMMENT \'drop\'',
				'site_url' => Schema::TYPE_STRING . '(32) NOT NULL COMMENT \'drop\'',
				'site_title' => Schema::TYPE_STRING . '(256) NOT NULL COMMENT \'drop\'',
				'site_keywords' => Schema::TYPE_STRING . '(256) NOT NULL COMMENT \'drop\'',
				'site_description' => Schema::TYPE_STRING . '(256) NOT NULL COMMENT \'drop\'',
				'site_creation' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\'',
				'site_dateformat' => Schema::TYPE_STRING . '(8) NOT NULL',
				'site_timeformat' => Schema::TYPE_STRING . '(8) NOT NULL',
				'construction_date' => Schema::TYPE_DATE . ' NOT NULL DEFAULT \'0000-00-00\' COMMENT \'drop\'',
				'construction_text' => Schema::TYPE_TEXT . ' NOT NULL COMMENT \'drop\'',
				'event_startdate' => Schema::TYPE_DATE . ' NOT NULL DEFAULT \'0000-00-00\' COMMENT \'drop\'',
				'event_finishdate' => Schema::TYPE_DATE . ' NOT NULL DEFAULT \'0000-00-00\' COMMENT \'drop\'',
				'event_tag' => Schema::TYPE_TEXT . ' NOT NULL COMMENT \'drop\'',
				'signup_username' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'signup_approve' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'signup_verifyemail' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'signup_photo' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'signup_welcome' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'signup_random' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'signup_terms' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'signup_invitepage' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'signup_inviteonly' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'signup_checkemail' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'signup_numgiven' => Schema::TYPE_TINYINT . '(3) NOT NULL DEFAULT \'5\'',
				'signup_adminemail' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'general_profile' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'general_invite' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'general_search' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'general_portal' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'general_include' => Schema::TYPE_TEXT . ' COMMENT \'drop\'',
				'general_commenthtml' => Schema::TYPE_STRING . '(256) NOT NULL',
				'lang_allow' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'lang_autodetect' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'lang_anonymous' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'banned_ips' => Schema::TYPE_TEXT,
				'banned_emails' => Schema::TYPE_TEXT,
				'banned_usernames' => Schema::TYPE_TEXT,
				'banned_words' => Schema::TYPE_TEXT,
				'spam_comment' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'spam_contact' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'spam_invite' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'spam_login' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'spam_failedcount' => Schema::TYPE_TINYINT . '(2) NOT NULL DEFAULT \'10\'',
				'spam_signup' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'analytic' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\' COMMENT \'drop\'',
				'analytic_id' => Schema::TYPE_STRING . '(32) NOT NULL COMMENT \'drop\'',
				'analytic_profile_id' => Schema::TYPE_STRING . '(32) NOT NULL COMMENT \'drop\'',
				'license_email' => Schema::TYPE_STRING . '(64) NOT NULL',
				'license_key' => Schema::TYPE_STRING . '(32) NOT NULL',
				'ommu_version' => Schema::TYPE_STRING . '(8) NOT NULL',
				'migrate' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'0\' COMMENT \'drop\'',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'PRIMARY KEY ([[id]])',
			], $tableOptions);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_core_settings}}';
		$this->dropTable($tableName);
	}
}
