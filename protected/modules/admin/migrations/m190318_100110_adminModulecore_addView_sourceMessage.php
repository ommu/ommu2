<?php
/**
 * m190318_100110_adminModulecore_addView_sourceMessage
 *
 * @author Putra Sudaryanto <dwptr@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 3 June 2023, 21:41 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use Yii;
use yii\db\Schema;

class m190318_100110_adminModulecore_addView_sourceMessage extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP VIEW IF EXISTS `_source_message`');

		// alter view _source_message
		$alterViewSourceMessage = <<< SQL
CREATE VIEW `_source_message` AS
select
  `a`.`id` AS `id`,
  count(`b`.`id`) AS `translates`
from (`source_message` `a`
   left join `message` `b`
     on (`a`.`id` = `b`.`id`))
group by `a`.`id`;
SQL;
		$this->execute($alterViewSourceMessage);
	}

	public function down()
	{
		$this->execute('DROP VIEW IF EXISTS `_source_message`');
	}
}
