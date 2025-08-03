<?php
/**
 * admin module definition class
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 22 December 2017, 03:29 WIB
 * @link https://github.com/ommu/ommu2
 *
 */

namespace app\modules\admin;

use Yii;

class Module extends \ommu\core\Module
{
	public $layout = 'main';

	/**
	 * {@inheritdoc}
	 */
	public $controllerNamespace = 'ommu\core\controllers';

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		parent::init();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getViewPath() 
	{
		if (preg_match('/app/', get_class(Yii::$app->controller))) {
			return Yii::getAlias('@app/modules/admin/views');
        }

		return Yii::getAlias('@ommu/core/views');
	}
}
