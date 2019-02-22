<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\components;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * ActiveForm is a widget that builds an interactive HTML form for one or multiple data models.
 *
 * For more details and usage information on ActiveForm, see the [guide article on forms](guide:input-forms).
 * @see yii\widgets\ActiveForm
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 11 January 2019, 11:11 WIB
 * @link https://github.com/ommu/ommu
 */
class ActiveForm extends \yii\widgets\ActiveForm
{
	/**
	 * {@inheritdoc}
	 */
	public $fieldClass = 'app\components\widgets\ActiveField';

	/**
	 * Initializes the widget.
	 * This renders the form open tag.
	 */
	public function init()
	{
		parent::init();
	}

	/**
	 * {@inheritdoc}
	 */
	public function field($model, $attribute, $options=[])
	{
		$horizontal = isset($this->options['class']) && preg_match('/(form-horizontal)/', $this->options['class']) ? true : false;
		if(!array_key_exists('template', $options) && $horizontal)
			$options = ArrayHelper::merge(['template'=>'{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}{hint}</div>'], $options);

		$config = $this->fieldConfig;
		if ($config instanceof \Closure) {
			$config = call_user_func($config, $model, $attribute);
		}
		if (!isset($config['class'])) {
			$config['class'] = $this->fieldClass;
			if($horizontal) {
				$config['options'] = ['class'=>'form-group row'];
				$config['labelOptions'] = ['class'=>'control-label col-md-3 col-sm-3 col-xs-12'];
			}
		}

		return Yii::createObject(ArrayHelper::merge($config, $options, [
			'model' => $model,
			'attribute' => $attribute,
			'form' => $this,
		]));
	}
}
