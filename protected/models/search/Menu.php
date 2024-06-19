<?php
/**
 * Menu
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 5 August 2019, 15:58 WIB
 * @link https://github.com/ommu/ommu
 *
 */

namespace app\models\search;

use Yii;
use yii\helpers\ArrayHelper;

class Menu extends \mdm\admin\models\searchs\Menu
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		if (($app = Yii::$app->request->get('app')) != null) {
			$params = require(join('/', [dirname(Yii::getAlias('@webroot')), $app, 'app/config', 'params.php']));
			Yii::$app->params = ArrayHelper::merge(Yii::$app->params, $params);
		}
		return parent::init();
	}
}
