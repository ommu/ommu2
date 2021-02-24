<?php
/**
 * Source Messages (source-message)
 * @var $this app\components\View
 * @var $this app\controllers\PhraseController
 * @var $model app\models\SourceMessage
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 6 December 2019, 10:32 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
?>

<div class="source-message-form">

<?php $form = ActiveForm::begin([
	'options' => ['class' => 'form-horizontal form-label-left'],
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
	'fieldConfig' => [
		'errorOptions' => [
			'encode' => false,
		],
	],
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php echo $form->field($model, 'category')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('category')); ?>

<?php echo $form->field($model, 'message')
	->textarea(['rows' => 4, 'cols' => 50])
	->label($model->getAttributeLabel('message')); ?>

<?php echo $form->field($model, 'location')
	->textarea(['rows' => 3, 'cols' => 50])
	->label($model->getAttributeLabel('location')); ?>

<?php if(!empty($model->languages)) {?>
<hr/>

<?php foreach ($model->languages as $key => $val) {
		echo $form->field($model, "translate[$key]")
			->textarea(['rows' => 4, 'cols' => 50])
			->label($val);
	}
}?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>