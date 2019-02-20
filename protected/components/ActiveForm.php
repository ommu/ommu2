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
	 * Initializes the widget.
	 * This renders the form open tag.
	 */
	public function init()
	{
		parent::init();
		if (!isset($this->options['class']))
			$this->options['class'] = 'form-horizontal form-label-left';
	}

	/**
	 * {@inheritdoc}
	 */
	public function field($model, $attribute, $options=[])
	{
		$bootstrap = '4';
		if(!array_key_exists('template', $options)) {
			if($bootstrap == 4)
				$options = ArrayHelper::merge(['template'=>'{label}{input}{hint}{error}'], $options);
		}

		return parent::field($model, $attribute, $options);
	}
}
