<?php
/**
 * rbac module definition class
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 26 December 2017, 12:38 WIB
 * @link https://github.com/ommu/ommu
 *
 */

namespace app\modules\rbac;

use Yii;

class Module extends \mdm\admin\Module
{
	public $layout = 'main';

	/**
	 * {@inheritdoc}
	 */
	public $controllerNamespace = 'mdm\admin\controllers';

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
		$controller = strtolower(Yii::$app->controller->id);
		if (in_array($controller, Yii::$app->params['rbacDontLoadController'])) {
			throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

		if (!in_array($controller, ['permission', 'role']) && !file_exists(join('/', [parent::getViewPath(), $controller]))) {
			return Yii::getAlias('@mdm/admin/views');
        }

		return parent::getViewPath();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getLayoutPath()
	{
		if (Yii::$app->view->theme) {
			return Yii::$app->view->theme->basePath . DIRECTORY_SEPARATOR . 'layouts';
        } else {
			return parent::getLayoutPath();
        }
	}
}
