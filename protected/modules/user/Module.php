<?php
/**
 * user module definition class
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 3 January 2018, 14:02 WIB
 * @link https://github.com/ommu/ommu
 *
 */

namespace app\modules\user;

use Yii;

class Module extends \ommu\users\Module
{
	/**
	 * {@inheritdoc}
	 */
	public $controllerNamespace = 'app\modules\user\controllers';

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		parent::init();
	}
}
