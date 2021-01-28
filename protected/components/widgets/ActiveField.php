<?php
/**
 * ActiveField represents a form input field within an [[ActiveForm]].
 *
 * For more details and usage information on ActiveField, see the [guide article on forms](guide:input-forms).
 * @see yii\bootstrap\ActiveField
 * @see yii\widgets\ActiveField
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
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
	public $horizontalSubmitButtonTemplate = "{beginWrapper}\n{input}\n{endWrapper}";

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
		if(isset($config['horizontalCssClasses']['hint'])) {
			if($this instanceof \yii\bootstrap4\ActiveField) {
				Html::addCssClass($config['hintOptions'], join(' ', ['form-text', 'text-muted', $config['horizontalCssClasses']['hint']]));
			} else
				Html::addCssClass($config['hintOptions'], $config['horizontalCssClasses']['hint']);
		}

		parent::__construct($config);
	}

	/**
	 * {@inheritdoc}
	 */
	public function submitButton($options = [])
	{
		if(!isset($this->template) || (isset($this->template) && $this->template === $this->form->fieldConfig['template'])) {
			if (!isset($options['template'])) {
				$this->template = $this->form->layout === 'horizontal' ?
					$this->horizontalSubmitButtonTemplate : $this->horizontalSubmitButtonTemplate;
			} else {
				$this->template = $options['template'];
				unset($options['template']);
			}
        }
        $offset = true;
        if (isset($options['offset']) && $options['offset'] == false) {
            $offset = false;
        }
		if ($this->form->layout === 'horizontal' && $offset) {
			Html::addCssClass($this->wrapperOptions, $this->horizontalCssClasses['offset']);
		}
		$this->labelOptions['class'] = null;
		$options['label'] = null;

		$model = $this->model;
		$button = $options['button'];
		if(!isset($options['button']))
			$button = Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
		$this->parts['{input}'] = $button;

		return $this;
	}
}
