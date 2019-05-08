<?php
/**
 * ActiveForm is a widget that builds an interactive HTML form for one or multiple data models.
 *
 * For more details and usage information on ActiveForm, see the [guide article on forms](guide:input-forms).
 * @see yii\bootstrap\ActiveForm
 * @see yii\widgets\ActiveForm
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
use yii\helpers\ArrayHelper;

class ActiveForm extends \yii\bootstrap\ActiveForm
{
	/**
	 * {@inheritdoc}
	 */
	public static function begin($config = [])
	{
		$config = ArrayHelper::merge(
			$config, 
			self::getLayout($config)
		);
		if(isset(Yii::$app->view->themeSetting['bootstrap4']) && Yii::$app->view->themeSetting['bootstrap4']) {
			$config = ArrayHelper::merge(
				$config, 
				['fieldConfig' => [
					'labelOptions' => [
						'class' => ['col-form-label', 'col-md-4 col-sm-3 col-12'],
					],
					'wrapperOptions' => [
						'class' => 'col-md-8 col-sm-9 col-12',
					],
				]],
			);
		} else {
			$config = ArrayHelper::merge(
				$config, 
				['fieldConfig' => [
					'options' => [
						'class' => ['form-group', 'row'],
					],
					'labelOptions' => [
						'class' => ['control-label', 'col-md-3 col-sm-3 col-xs-12'],
					],
					'wrapperOptions' => [
						'class' => 'col-md-6 col-sm-9 col-xs-12',
					],
					'hintOptions' => [
						'tag' => 'div',
						'class' => 'hint-block',
					],
					'errorOptions' => [
						'tag' => 'div',
						'class' => ['help-block', 'help-block-error'],
					],
				]],
			);
			if(self::getLayout($config)['layout'] === 'horizontal') {
				$config = ArrayHelper::merge(
					$config, 
					['fieldConfig' => [
						'template' => "{label}\n{beginWrapper}\n{input}\n{error}\n{hint}\n{endWrapper}",
					]],
				);
			}
		}

		$parentClass = get_parent_class();
		if(isset(Yii::$app->view->themeSetting['bootstrap4']) && Yii::$app->view->themeSetting['bootstrap4'])
			$parentClass = 'yii\bootstrap4\ActiveForm';

		return  $parentClass::begin($config);
	}

	/**
	 * {@inheritdoc}
	 */
	public static function end()
	{
		$parentClass = get_parent_class();
		if(isset(Yii::$app->view->themeSetting['bootstrap4']) && Yii::$app->view->themeSetting['bootstrap4'])
			$parentClass = 'yii\bootstrap4\ActiveForm';

		return  $parentClass::end($config);
	}

	/**
	 * {@inheritdoc}
	 */
	public static function getLayout($config)
	{
		if(!isset($config['options']['class']))
			return [];

		if(preg_match('/(form-horizontal)/', $config['options']['class']))
			return ['layout'=>'horizontal'];

		if(preg_match('/(form-inline)/', $config['options']['class']))
			return ['layout'=>'inline'];
	}
}
