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
 * @modified date 27 February 2019, 12:47 WIB
 * @link https://github.com/ommu/ommu
 */

namespace app\components\widgets;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class ActiveField extends \yii\bootstrap\ActiveField
{
	/**
	 * @var string the template for checkboxes in horizontal layout
	 */
	public $horizontalCheckboxTemplate = "{beginWrapper}\n<div class=\"checkbox\">\n{beginLabel}\n{input}\n{labelTitle}\n{endLabel}\n</div>\n{error}\n{hint}\n{endWrapper}";

	/**
	 * @param array $instanceConfig the configuration passed to this instance's constructor
	 * @return array the layout specific default configuration for this instance
	 */
	protected function createLayoutConfig($instanceConfig)
	{
		$layoutConfig  = parent::createLayoutConfig($instanceConfig);
		$layout = $instanceConfig['form']->layout;

		$layoutConfig = ArrayHelper::merge(
			$layoutConfig, [
				'hintOptions' => [
					'tag' => 'div',
					'class' => 'hint-block',
				],
				'errorOptions' => [
					'tag' => 'div',
					'class' => 'help-block',
				],
			]);

		if ($layout === 'horizontal') {
			$config['template'] = "{label}\n{beginWrapper}\n{input}\n{error}\n{hint}\n{endWrapper}";
			$cssClasses = array_merge([
				'offset' => 'offset-sm-3',
				'label' => 'col-md-3 col-sm-3 col-12',
				'wrapper' => 'col-md-6 col-sm-9 col-12',
				'error' => '',
				'hint' => '',
			], $this->horizontalCssClasses);
			if (isset($instanceConfig['horizontalCssClasses'])) {
				$cssClasses = ArrayHelper::merge($cssClasses, $instanceConfig['horizontalCssClasses']);
			}
			$config['horizontalCssClasses'] = $cssClasses;
			$config['options'] = ['class' => 'form-group row'];
			$config['wrapperOptions'] = ['class' => $cssClasses['wrapper']];
			$config['labelOptions'] = ['class'=>'control-label'];
			if($cssClasses['label'])
				Html::addCssClass($config['labelOptions'], $cssClasses['label']);
			$config['errorOptions'] = ['class'=>'help-block'];
			if($cssClasses['error'])
				Html::addCssClass($config['errorOptions'], $cssClasses['error']);
			$config['hintOptions'] = ['class'=>'hint-block'];
			if($cssClasses['hint'])
				Html::addCssClass($config['hintOptions'], $cssClasses['hint']);
			
			$layoutConfig = ArrayHelper::merge($layoutConfig, $config);
		}

		return $layoutConfig;
	}

	/**
	 * Makes field remember its value between page reloads
	 * @return $this the field object itself
	 */
	public function sticky()
	{
		$this->options['class'] .= ' sticky';

		return $this;
	}
}
