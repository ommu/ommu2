<?php
/**
 * ActiveForm is a widget that builds an interactive HTML form for one or multiple data models.
 *
 * For more details and usage information on ActiveForm, see the [guide article on forms](guide:input-forms).
 * @see yii\bootstrap\ActiveForm
 * @see yii\widgets\ActiveForm
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 11 January 2019, 11:11 WIB
 * @modified date 27 February 2019, 12:47 WIB
 * @link https://github.com/ommu/ommu
 */

namespace app\components\widgets;

use Yii;
use yii\helpers\ArrayHelper;

function get_form_parent() {
	if (isset(Yii::$app->view->themeSetting['bootstrap4']) && Yii::$app->view->themeSetting['bootstrap4']) {
		return 'yii\bootstrap4\ActiveForm';
    }

	return 'yii\bootstrap\ActiveForm';
}
class_alias(get_form_parent(), 'app\components\widgets\OActiveForm');

class ActiveForm extends OActiveForm
{
	/**
	 * {@inheritdoc}
	 */
	public $fieldClass = 'app\components\widgets\ActiveField';

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		if (isset($this->options['class'])) {
			if (preg_match('/(form-horizontal)/', $this->options['class'])) {
				$this->layout = 'horizontal';
            }
			if (preg_match('/(form-inline)/', $this->options['class'])) {
				$this->layout = 'inline';
            }
		}

		$submenuOnLayout = false;
        if (!(Yii::$app->view->context instanceof \yii\base\Widget)) {
            if (Yii::$app->view->submenuOnLayout || in_array(strtolower(Yii::$app->view->context->module->id), ['gii', 'rbac'])) {
                $submenuOnLayout = true;
            }
        }

		if (isset(Yii::$app->view->themeSetting['bootstrap4']) && Yii::$app->view->themeSetting['bootstrap4']) {
			$fieldConfig = [
				'options' => [
					'class' => ['form-group', 'row'],
				],
				'labelOptions' => [
					'class' => ['col-form-label', (!$submenuOnLayout ? 'col-md-4 col-sm-3 col-12' : 'col-sm-3 col-12')],
				],
				'wrapperOptions' => [
					'class' => !$submenuOnLayout ? 'col-md-8 col-sm-9 col-12' : 'col-sm-9 col-12',
				],
			];
			if ($this->layout === 'horizontal') {
				$fieldConfig = ArrayHelper::merge(
					$fieldConfig, 
					[
						'template' => "{label}\n{beginWrapper}\n{input}\n{error}\n{hint}\n{endWrapper}",
						'horizontalCssClasses' => [
							'offset' => !$submenuOnLayout ? 'offset-md-4 offset-sm-3' : 'offset-sm-3',
						],
					]
				);
			}
		} else {
			$fieldConfig = [
				'options' => [
					'class' => ['form-group', 'row'],
				],
				'labelOptions' => [
					'class' => ['control-label', 'col-md-3 col-sm-3 col-xs-12'],
				],
				'wrapperOptions' => [
					'class' => !Yii::$app->request->isAjax ? (!$submenuOnLayout ? 'col-md-6 col-sm-9 col-xs-12' : 'col-md-9 col-sm-9 col-xs-12') : 'col-md-9 col-sm-9 col-xs-12',
				],
				'hintOptions' => [
					'tag' => 'div',
					'class' => 'hint-block',
				],
				'errorOptions' => [
					'tag' => 'div',
					'class' => 'help-block help-block-error',
				],
			];
			if ($this->layout === 'horizontal') {
				$fieldConfig = ArrayHelper::merge(
					$fieldConfig, 
					[
						'template' => "{label}\n{beginWrapper}\n{input}\n{error}\n{hint}\n{endWrapper}",
						'horizontalCssClasses' => [
							'offset' => 'col-sm-offset-3',
						],
					]
				);
			}
		}

		if (!empty($this->fieldConfig)) {
			$fieldConfig = ArrayHelper::merge($fieldConfig, $this->fieldConfig);
        }

		$this->fieldConfig = $fieldConfig;

		parent::init();
	}
}
