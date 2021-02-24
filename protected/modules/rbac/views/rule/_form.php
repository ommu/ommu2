<?php
/**
 * @var $this  yii\web\View
 * @var $model mdm\admin\models\BizRule
 * @var $form ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 28 December 2017, 06:50 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
?>

<div class="auth-item-form">

<?php $form = ActiveForm::begin([
	'options' => ['class' => 'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php echo $form->field($model, 'name')
	->textInput(['maxlength' => 64])
	->label($model->getAttributeLabel('name')); ?>

<?php echo $form->field($model, 'className')
	->textInput()
	->label($model->getAttributeLabel('className')); ?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(['button' => Html::submitButton($model->isNewRecord ? Yii::t('rbac-admin', 'Create') : Yii::t('rbac-admin', 'Update'), ['class' => ($model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'), 'name' => 'submit-button'])]); ?>

<?php ActiveForm::end(); ?>

</div>
