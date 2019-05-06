<?php
/**
 * ActiveForm is a widget that builds an interactive HTML form for one or multiple data models.
 *
 * For more details and usage information on ActiveForm, see the [guide article on forms](guide:input-forms).
 * @see yii\widgets\ActiveForm
 * @see yii\bootstrap\ActiveForm
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 11 January 2019, 11:11 WIB
 * @modified date 27 February 2019, 12:47 WIB
 * @link https://github.com/ommu/ommu
 */

namespace app\components\widgets;

use Yii;

class ActiveForm extends \yii\bootstrap\ActiveForm
{
	/**
	 * @var string the default field class name when calling [[field()]] to create a new field.
	 * @see fieldConfig
	 */
	public $fieldClass = 'app\components\widgets\ActiveField';

	/**
	 * Initializes the widget.
	 * This renders the form open tag.
	 */
	public function init()
	{
		if(isset($this->options['class'])) {
			if(preg_match('/(form-horizontal)/', $this->options['class']))
				$this->layout = 'horizontal';
			if(preg_match('/(form-inline)/', $this->options['class']))
				$this->layout = 'inline';
		}
		parent::init();
	}
}
