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
use yii\widgets\ActiveForm;
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
	'options' => [
		'class' => 'form-horizontal form-label-left',
		//'enctype' => 'multipart/form-data',
	],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php echo Html::activeHiddenInput($model, 'parent', ['id' => 'parent_id']); ?>

<?php echo $form->field($model, 'name', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textInput(['maxlength' => 128])
	->label($model->getAttributeLabel('name'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'icon', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}</div>'])
	->widget('\insolita\iconpicker\Iconpicker', [
		'iconset'=>'fontawesome',
		'clientOptions'=>['rows'=>8,'cols'=>10,'placement'=>'right'],
	])
	->label($model->getAttributeLabel('icon'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'parent_name', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textInput(['id' => 'parent_name'])
	->label($model->getAttributeLabel('parent_name'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'route', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textInput(['id' => 'route'])
	->label($model->getAttributeLabel('route'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'order', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}</div>'])
	->input('number')
	->label($model->getAttributeLabel('order'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'data', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textarea(['rows' => 4])
	->label($model->getAttributeLabel('data'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<div class="ln_solid"></div>
<div class="form-group">
	<div class="col-md-9 col-sm-9 col-xs-12 col-sm-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('rbac-admin', 'Create') : Yii::t('rbac-admin', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>
