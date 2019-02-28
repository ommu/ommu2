<?php
/**
 * ActiveField represents a form input field within an [[ActiveForm]].
 *
 * For more details and usage information on ActiveField, see the [guide article on forms](guide:input-forms).
 * @see yii\widgets\ActiveField
 * @see yii\bootstrap\ActiveField
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
use yii\helpers\ArrayHelper;

class ActiveField extends \yii\bootstrap\ActiveField
{
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
				'offset' => 'col-sm-offset-3',
				'label' => 'col-sm-3',
				'wrapper' => 'col-sm-6',
				'error' => '',
				'hint' => '',
			], $this->horizontalCssClasses);
			if (isset($instanceConfig['horizontalCssClasses'])) {
				$cssClasses = ArrayHelper::merge($cssClasses, $instanceConfig['horizontalCssClasses']);
			}
			$config['horizontalCssClasses'] = $cssClasses;
			$config['options'] = ['class' => 'form-group row'];
			$config['labelOptions'] = ['class' => 'control-label ' . $cssClasses['label']];
			$config['wrapperOptions'] = ['class' => $cssClasses['wrapper']];
			$config['errorOptions']['class'] = 'help-block ' . $cssClasses['error'];
			$config['hintOptions']['class'] = 'hint-block ' . $cssClasses['hint'];
			
			$layoutConfig = ArrayHelper::merge($layoutConfig, $config);
		}

		return $layoutConfig;
	}
}
