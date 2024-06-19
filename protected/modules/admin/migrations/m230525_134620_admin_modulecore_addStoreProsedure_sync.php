<?php
/**
 * m230525_134620_admin_modulecore_addStoreProsedure_sync
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 25 May 2023, 21:42 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\db\Schema;

class m230525_134620_admin_modulecore_addStoreProsedure_sync extends \yii\db\Migration
{
	public function up()
	{
        $this->execute('DROP PROCEDURE IF EXISTS `syncGetMessage`');
        $this->execute('DROP PROCEDURE IF EXISTS `syncSlug`');

		// alter sp syncGetMessage
		$alterProsedureSyncGetMessage = <<< SQL
CREATE PROCEDURE `syncGetMessage`(IN id_sp INT, OUT message_sp TEXT)
BEGIN
	SELECT `message` 
	INTO message_sp 
	FROM `source_message` WHERE `id`=id_sp;
END;
SQL;
		$this->execute($alterProsedureSyncGetMessage);

		// alter sp syncSlug
		$alterProsedureSyncSlug = <<< SQL
CREATE PROCEDURE `syncSlug`(IN `string_sp` TEXT, OUT `slug_sp` TEXT)
BEGIN
	SELECT LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(TRIM(string_sp), ':', ''), '’', ''), ')', ''), '(', ''), ',', ''), '\\', ''), '\/', ''), '\"', ''), '?', ''), '\'', ''), '&', ''), '!', ''), '.', ''), ' ', '-'), '--', '-'), '--', '-')) INTO slug_sp;
END;
SQL;
		$this->execute($alterProsedureSyncSlug);
	}

	public function down()
	{
        $this->execute('DROP PROCEDURE `syncGetMessage`');
        $this->execute('DROP PROCEDURE `syncSlug`');
	}
}
