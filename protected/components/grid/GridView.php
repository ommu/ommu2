<?php
/**
 * GridView for OMMU
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 2 May 2019, 09:39 WIB
 * @link https://github.com/ommu/ommu
 */

namespace app\components\grid;

use Yii;
use yii\helpers\ArrayHelper;

class GridView extends \yii\grid\GridView
{
	/**
	 * {@inheritdoc}
	 */
	public static function widget($config = [])
	{
		$parentClass = get_parent_class();
		if(isset(Yii::$app->view->themeSetting['GridView']))
			$parentClass = Yii::$app->view->themeSetting['GridView'];

		return $parentClass::widget($config);
	}
}
