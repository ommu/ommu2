<?php
/**
 * @var $this yii\web\View
 * @var $model mdm\admin\models\Menu
 * @var $form yii\widgets\ActiveForm
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
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php echo Html::activeHiddenInput($model, 'parent', ['id' => 'parent_id']); ?>

<?php echo $form->field($model, 'name', ['horizontalCssClasses' => ['wrapper'=>'col-sm-9 col-xs-12 col-12']])
	->textInput(['maxlength' => 128])
	->label($model->getAttributeLabel('name')); ?>

<?php echo $form->field($model, 'icon', ['horizontalCssClasses' => ['wrapper'=>'col-sm-9 col-xs-12 col-12']])
	->widget('\insolita\iconpicker\Iconpicker', [
		'iconset'=>'fontawesome',
		'clientOptions'=>['rows'=>8,'cols'=>10,'placement'=>'right'],
	])
	->label($model->getAttributeLabel('icon')); ?>

<?php echo $form->field($model, 'parent_name', ['horizontalCssClasses' => ['wrapper'=>'col-sm-9 col-xs-12 col-12']])
	->textInput(['id' => 'parent_name'])
	->label($model->getAttributeLabel('parent_name')); ?>

<?php echo $form->field($model, 'route', ['horizontalCssClasses' => ['wrapper'=>'col-sm-9 col-xs-12 col-12']])
	->textInput(['id' => 'route'])
	->label($model->getAttributeLabel('route')); ?>

<?php echo $form->field($model, 'order', ['horizontalCssClasses' => ['wrapper'=>'col-sm-9 col-xs-12 col-12']])
	->input('number')
	->label($model->getAttributeLabel('order')); ?>

<?php echo $form->field($model, 'data', ['horizontalCssClasses' => ['wrapper'=>'col-sm-9 col-xs-12 col-12']])
	->textarea(['rows' => 4])
	->label($model->getAttributeLabel('data')); ?>

<?php echo $form->field($model, 'public', ['horizontalCssClasses' => ['wrapper'=>'col-sm-9 col-xs-12 col-12']])
	->checkbox()
	->label($model->getAttributeLabel('public')); ?>

<div class="ln_solid"></div>
<div class="form-group row">
	<div class="col-sm-9 col-xs-12 col-12 col-sm-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('rbac-admin', 'Create') : Yii::t('rbac-admin', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>
