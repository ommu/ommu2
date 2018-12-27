<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mdm\admin\components\RouteRule;
use mdm\admin\AutocompleteAsset;
use yii\helpers\Json;
use mdm\admin\components\Configs;

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\AuthItem */
/* @var $form yii\widgets\ActiveForm */
/* @var $context mdm\admin\components\ItemController */

$context = $this->context;
$labels = $context->labels();
$rules = Configs::authManager()->getRules();
unset($rules[RouteRule::RULE_NAME]);
$source = Json::htmlEncode(array_keys($rules));

$js = <<<JS
    $('#rule_name').autocomplete({
        source: $source,
    });
JS;
AutocompleteAsset::register($this);
$this->registerJs($js);
?>

<div class="auth-item-form">

<?php $form = ActiveForm::begin([
	'id' => 'item-form',
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

<?php echo $form->field($model, 'description', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textarea(['rows' => 2])
	->label($model->getAttributeLabel('description'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'ruleName', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textInput(['id' => 'rule_name'])
	->label($model->getAttributeLabel('ruleName'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'data', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}</div>']
	)->textarea(['rows' => 6])
	->label($model->getAttributeLabel('data'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<div class="ln_solid"></div>
<div class="form-group">
	<div class="col-md-9 col-sm-9 col-xs-12 col-sm-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('rbac-admin', 'Create') : Yii::t('rbac-admin', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
		'name' => 'submit-button']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>
