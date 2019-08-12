<?php
/**
 * SourceMessage
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 15 September 2017, 19:07 WIB
 * @modified date 22 April 2017, 18:28 WIB
 * @link https://github.com/ommu/ommu
 *
 * This is the model class for table "source_message".
 *
 */

namespace app\models;

use app\components\i18n\Configs;

class SourceMessage extends \ommu\core\models\SourceMessage
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return Configs::instance()->sourceTable;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function getDb()
	{
		if (Configs::instance()->db !== null)
			return Configs::instance()->db;
		else
			return parent::getDb();
	}
}
