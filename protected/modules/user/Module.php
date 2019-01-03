<?php
/**
 * user module definition class
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
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
