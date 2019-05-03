<?php
/**
 * ActionColumn for OMMU
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 3 May 2019, 13:22 WIB
 * @link https://github.com/ommu/ommu
 */

namespace app\components\grid;

use Yii;

class ActionColumn extends \yii\grid\ActionColumn
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		parent::init();

		$this->contentOptions = ['class'=>'action-column'];
	}

	/**
	 * {@inheritdoc}
	 */
	public static function widget($config = [])
	{
		$parentClass = get_parent_class();
		if(isset(Yii::$app->view->themeSetting['widget_class']['ActionColumn']))
			$parentClass = Yii::$app->view->themeSetting['widget_class']['ActionColumn'];

		return $parentClass::widget($config);
	}
}
