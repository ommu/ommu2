<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this  yii\web\View */
/* @var $model mdm\admin\models\BizRule */
/* @var $form ActiveForm */
?>

<div class="auth-item-form">

<?php $form = ActiveForm::begin([
	'options' => [
		'class' => 'form-horizontal form-label-left',
		//'enctype' => 'multipart/form-data',
	],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php echo $form->field($model, 'name', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textInput(['maxlength' => 64])
	->label($model->getAttributeLabel('name'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'className', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textInput()
	->label($model->getAttributeLabel('className'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<div class="ln_solid"></div>
<div class="form-group">
	<div class="col-md-9 col-sm-9 col-xs-12 col-sm-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('rbac-admin', 'Create') : Yii::t('rbac-admin', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
		'name' => 'submit-button']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>
