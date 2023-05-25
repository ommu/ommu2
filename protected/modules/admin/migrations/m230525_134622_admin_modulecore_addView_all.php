<?php
/**
 * m230525_134622_admin_modulecore_addView_all
 *
 * @author Putra Sudaryanto <dwptr@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 25 May 2023, 10:00 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use Yii;
use yii\db\Schema;

class m230525_134622_admin_modulecore_addView_all extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP VIEW IF EXISTS `_core_pages`');

		// alter view _core_pages
		$alterViewCorePages = <<< SQL
CREATE VIEW `_core_pages` AS
select
  `a`.`page_id` AS `page_id`,
  sum(case when `b`.`publish` = '1' then `b`.`views` else 0 end) AS `views`,
  sum(`b`.`views`) AS `view_all`
from (`_client_bpadjogja_v2`.`ommu_core_pages` `a`
   left join `_client_bpadjogja_v2`.`ommu_core_page_views` `b`
     on (`a`.`page_id` = `b`.`page_id`))
group by `a`.`page_id`;
SQL;
		$this->execute($alterViewCorePages);
	}

	public function down()
	{
		$this->execute('DROP VIEW IF EXISTS `_core_pages`');
	}
}
