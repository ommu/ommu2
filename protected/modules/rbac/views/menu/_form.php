<?php
/**
 * @var $this yii\web\View
 * @var $model mdm\admin\models\Menu
 * @var $form yii\widgets\ActiveForm
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
use app\models\Menu;
use yii\helpers\Json;
use mdm\admin\AutocompleteAsset;

AutocompleteAsset::register($this);
$opts = Json::htmlEncode([
		'menus' => Menu::getMenuSource(),
		'routes' => Menu::getSavedRoutes(),
	]);
$this->registerJs("var _opts = $opts;");
$this->registerJs($this->render('_script.js'));
?>

<div class="menu-form">

<?php $form = ActiveForm::begin([
	'options' => ['class' => 'form-horizontal form-label-left'],
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php echo Html::activeHiddenInput($model, 'parent', ['id' => 'parent_id']); ?>

<?php echo $form->field($model, 'name')
	->textInput(['maxlength' => 128])
	->label($model->getAttributeLabel('name')); ?>

<?php echo $form->field($model, 'icon')
	->widget('\insolita\iconpicker\Iconpicker', [
		'iconset' => 'fontawesome',
		'clientOptions'=>['rows'=>8,'cols'=>10,'placement' => 'right'],
	])
	->label($model->getAttributeLabel('icon')); ?>

<?php echo $form->field($model, 'parent_name')
	->textInput(['id' => 'parent_name'])
	->label($model->getAttributeLabel('parent_name')); ?>

<?php echo $form->field($model, 'route')
	->textInput(['id' => 'route'])
	->label($model->getAttributeLabel('route')); ?>

<?php echo $form->field($model, 'order')
	->input('number')
	->label($model->getAttributeLabel('order')); ?>

<?php echo $form->field($model, 'data')
	->textarea(['rows' => 4])
	->label($model->getAttributeLabel('data')); ?>

<?php echo $form->field($model, 'public')
	->checkbox()
	->label($model->getAttributeLabel('public')); ?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(['button'=>Html::submitButton($model->isNewRecord ? Yii::t('rbac-admin', 'Create') : Yii::t('rbac-admin', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])]); ?>

<?php ActiveForm::end(); ?>

</div>
