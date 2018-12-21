<?php
/**
 * gii module definition class
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 24 September 2017, 12:38 WIB
 * @link https://github.com/ommu/ommu
 * 
 * version 2.0.8
 * https://www.yiiframework.com/news/190/gii-extension-2-0-8-released
 *
 */

namespace app\modules\gii;

use Yii;
use yii\base\BootstrapInterface;

class Module extends \yii\gii\Module implements BootstrapInterface
{
	public $layout = 'generator';

	/**
	 * {@inheritdoc}
	 */
	public $controllerNamespace = 'app\modules\gii\controllers';

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		parent::init();

		// custom initialization code goes here
	}

	/**
	 * {@inheritdoc}
	 */
	public function getViewPath() 
	{
		return Yii::getAlias('@modules/gii/views');
	}

	/**
	 * {@inheritdoc}
	 */
	public function getLayoutPath()
	{
		if(Yii::$app->view->theme)
			return Yii::$app->view->theme->basePath . DIRECTORY_SEPARATOR . 'layouts';
		else
			return parent::getLayoutPath();
	}

	/**
	 * {@inheritdoc}
	 */
	public function bootstrap($app)
	{
		parent::bootstrap($app);
	}
}
