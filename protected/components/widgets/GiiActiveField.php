<?php
/**
 * ActiveField represents a form input field within an [[ActiveForm]].
 *
 * For more details and usage information on ActiveField, see the [guide article on forms](guide:input-forms).
 * @see yii\gii\components\ActiveField
 * @see yii\bootstrap\ActiveField
 * @see yii\widgets\ActiveField
 * @see app\components\widgets\ActiveField
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 27 February 2019, 10:47 WIB
 * @link https://github.com/ommu/ommu
 */

namespace app\components\widgets;

use Yii;
use yii\gii\Generator;
use yii\helpers\Json;
use yii\helpers\Html;

class GiiActiveField extends \app\components\widgets\ActiveField
{
	/**
	 * @var Generator
	 */
	public $model;

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		parent::init();

		$stickyAttributes = $this->model->stickyAttributes();
		if (in_array($this->attribute, $stickyAttributes, true)) {
			$this->sticky();
		}
		$hints = $this->model->hints();
		if (isset($hints[$this->attribute])) {
			$this->hint($hints[$this->attribute]);
		}
		$autoCompleteData = $this->model->autoCompleteData();
		if (isset($autoCompleteData[$this->attribute])) {
			if (is_callable($autoCompleteData[$this->attribute])) {
				$this->autoComplete(call_user_func($autoCompleteData[$this->attribute]));
			} else {
				$this->autoComplete($autoCompleteData[$this->attribute]);
			}
		}
	}

	/**
	 * Makes field remember its value between page reloads
	 * @return $this the field object itself
	 */
	public function sticky()
	{
		Html::addCssClass($this->options['class'], 'sticky');

		return $this;
	}

	/**
	 * Makes field auto completable
	 * @param array $data auto complete data (array of callables or scalars)
	 * @return $this the field object itself
	 */
	public function autoComplete($data)
	{
		static $counter = 0;
		$this->inputOptions['class'] .= ' typeahead typeahead-' . (++$counter);
		foreach ($data as &$item) {
			$item = ['word' => $item];
		}
		$this->form->getView()->registerJs("yii.gii.autocomplete($counter, " . Json::htmlEncode($data) . ");");

		return $this;
	}
}
