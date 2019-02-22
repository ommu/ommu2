<?php
/**
 * @var $this yii\web\View
 * @var $model mdm\admin\models\AuthItem
 * @var $form yii\widgets\ActiveForm
 * @var $context mdm\admin\components\ItemController
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 28 December 2017, 06:50 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\helpers\Html;
use app\components\ActiveForm;
use mdm\admin\components\RouteRule;
use mdm\admin\AutocompleteAsset;
use yii\helpers\Json;
use mdm\admin\components\Configs;

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
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php echo $form->field($model, 'name')
	->textInput(['maxlength' => 64])
	->label($model->getAttributeLabel('name')); ?>

<?php echo $form->field($model, 'description')
	->textarea(['rows' => 2])
	->label($model->getAttributeLabel('description')); ?>

<?php echo $form->field($model, 'ruleName')
	->textInput(['id' => 'rule_name'])
	->label($model->getAttributeLabel('ruleName')); ?>

<?php echo $form->field($model, 'data'
	)->textarea(['rows' => 6])
	->label($model->getAttributeLabel('data')); ?>

<div class="ln_solid"></div>
<div class="form-group row">
	<div class="col-md-9 col-sm-9 col-xs-12 offset-sm-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('rbac-admin', 'Create') : Yii::t('rbac-admin', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
		'name' => 'submit-button']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>
