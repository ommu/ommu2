<?php
/**
 * ListView for OMMU
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 18 September 2019, 09:10 WIB
 * @link https://github.com/ommu/ommu
 */

namespace app\components\widgets;

use Yii;

class ListView extends \yii\widgets\ListView
{
	/**
	 * {@inheritdoc}
	 */
	public static function widget($config = [])
	{
		$parentClass = get_parent_class();
		if (isset(Yii::$app->view->themeSetting['widget_class']['ListView'])) {
			$parentClass = Yii::$app->view->themeSetting['widget_class']['ListView'];
        }

		return $parentClass::widget($config);
	}
}
