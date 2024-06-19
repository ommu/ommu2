<?php
/**
 * Message
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 15 September 2017, 19:16 WIB
 * @modified date 22 April 2017, 18:28 WIB
 * @link https://github.com/ommu/ommu
 *
 * This is the model class for table "message".
 *
 */

namespace app\models;

use app\components\i18n\Configs;

class Message extends \ommu\core\models\Message
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return Configs::instance()->translateTable;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function getDb()
	{
		if (Configs::instance()->db !== null) {
			return Configs::instance()->db;
        } else {
			return parent::getDb();
        }
	}
}
