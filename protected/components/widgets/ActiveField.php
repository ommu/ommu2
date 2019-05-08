<?php
/**
 * ActiveField represents a form input field within an [[ActiveForm]].
 *
 * For more details and usage information on ActiveField, see the [guide article on forms](guide:input-forms).
 * @see yii\bootstrap\ActiveField
 * @see yii\widgets\ActiveField
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 21 February 2019, 15:29 WIB
 * @modified date 8 May 2019, 17:35 WIB
 * @link https://github.com/ommu/ommu
 */

namespace app\components\widgets;

use Yii;
use yii\helpers\Html;

function get_field_parent() {
	if(isset(Yii::$app->view->themeSetting['bootstrap4']) && Yii::$app->view->themeSetting['bootstrap4'])
		return 'yii\bootstrap4\ActiveField';

	return 'yii\bootstrap\ActiveField';
}
class_alias(get_field_parent(), 'app\components\widgets\OActiveField');

class ActiveField extends OActiveField
{
	/**
	 * {@inheritdoc}
	 */
	public function __construct($config = [])
	{
		if(isset($config['horizontalCssClasses']['wrapper']))
			$config['wrapperOptions']['class'] = $config['horizontalCssClasses']['wrapper'];
		if(isset($config['horizontalCssClasses']['label']))
			Html::addCssClass($config['labelOptions'], $config['horizontalCssClasses']['label']);
		if(isset($config['horizontalCssClasses']['error']))
			Html::addCssClass($config['errorOptions'], $config['horizontalCssClasses']['error']);
		if(isset($config['horizontalCssClasses']['hint']))
			Html::addCssClass($config['hintOptions'], $config['horizontalCssClasses']['hint']);
			
		$parentClass = get_parent_class();
		if(isset(Yii::$app->view->themeSetting['bootstrap4']) && Yii::$app->view->themeSetting['bootstrap4'])
			$parentClass = 'yii\bootstrap4\ActiveField';

		parent::__construct($config);
	}
}
