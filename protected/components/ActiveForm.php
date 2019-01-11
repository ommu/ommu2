<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\components;

use Yii;
use yii\widgets\ActiveForm as YiiActiveForm;

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
class ActiveForm extends YiiActiveForm
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
}
