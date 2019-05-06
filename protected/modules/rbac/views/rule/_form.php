<?php
/**
 * @var $this  yii\web\View
 * @var $model mdm\admin\models\BizRule
 * @var $form ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 28 December 2017, 06:50 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
?>

<div class="auth-item-form">

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php echo $form->field($model, 'name', ['horizontalCssClasses' => ['wrapper'=>'col-sm-9 col-xs-12 col-12']])
	->textInput(['maxlength' => 64])
	->label($model->getAttributeLabel('name')); ?>

<?php echo $form->field($model, 'className', ['horizontalCssClasses' => ['wrapper'=>'col-sm-9 col-xs-12 col-12']])
	->textInput()
	->label($model->getAttributeLabel('className')); ?>

<div class="ln_solid"></div>
<div class="form-group row">
	<div class="col-sm-9 col-xs-12 col-12 col-sm-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('rbac-admin', 'Create') : Yii::t('rbac-admin', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
		'name' => 'submit-button']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>
