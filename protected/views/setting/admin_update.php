<?php
/**
 * @var $this app\components\View
 * @var $this app\controllers\SettingController
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 22 April 2019, 23:47 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\widgets\ActiveForm;
use app\models\BaseSetting;
use yii\helpers\ArrayHelper;
use ommu\selectize\Selectize;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$backofficeThemePagination = $model->backoffice_theme_pagination ? $model->backoffice_theme_pagination : 'default';
$themePagination = $model->theme_pagination ? $model->theme_pagination : 'default';

$js = <<<JS
	var sublayout, pagination;
	var v_backend_sublayout = '$model->backoffice_theme_sublayout';
	var v_backend_pagination = '$backofficeThemePagination';
	var v_front_sublayout = '$model->theme_sublayout';
	var v_front_pagination = '$themePagination';

	$('#basesetting-online input[name="BaseSetting[online]"]').on('change', function() {
		var id = $(this).val();
		if(id == '1') {
			$('div#construction').hide();
		} else {
			$('div#construction').show();
			if(id == '0') {
				$('.field-basesetting-construction_text-comingsoon').hide();
				$('.field-basesetting-construction_text-maintenance').show();
			} else {
				$('.field-basesetting-construction_text-maintenance').hide();
				$('.field-basesetting-construction_text-comingsoon').show();
			}
		}
	});
JS;
$this->registerJs($js, \yii\web\View::POS_END);

$getSublayoutUrl = Url::to(['sublayout', 'theme'=>'']);
$getPaginationUrl = Url::to(['pagination', 'theme'=>'']);
?>

<div class="base-setting-form">

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php $appType = BaseSetting::getAppType();
echo $form->field($model, 'app_type')
	->dropDownList($appType, ['prompt'=>''])
	->label($model->getAttributeLabel('app_type')); ?>

<?php // echo $form->errorSummary($model);?>

<?php $nameSmall = $form->field($model, 'name[small]', ['template' => '<div class="h5">Small Name</div>{input}', 'options' => ['tag' => null]])
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('name')); ?>

<?php echo $form->field($model, 'name[long]', ['template' => '{beginLabel}{labelTitle}{hint}{endLabel}{beginWrapper}'.$nameSmall.'<div class="h5">Long Name</div>{input}{error}{endWrapper}'])
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('name'))
	->hint(Yii::t('app', 'Give your website a unique name. This will appear in the &lt;title&gt; tag throughout most of your site.')); ?>

<?php echo $form->field($model, 'pagetitle_template')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('pagetitle_template'))
	->hint(Yii::t('app', 'e.g. {title} | {small-name} - {long-name}')); ?>

<?php echo $form->field($model, 'description', ['template' => '{beginLabel}{labelTitle}{hint}{endLabel}{beginWrapper}{input}{error}{endWrapper}'])
	->textarea(['rows'=>3, 'cols'=>50, 'maxlength' => true])
	->label($model->getAttributeLabel('description'))
	->hint(Yii::t('app', 'Enter a brief, concise description of your website. Include any key words or phrases that you want to appear in search engine listings.')); ?>

<?php echo $form->field($model, 'keywords', ['template' => '{beginLabel}{labelTitle}{hint}{endLabel}{beginWrapper}{input}{error}{endWrapper}'])
	->textarea(['rows'=>4, 'cols'=>50, 'maxlength' => true])
	->label($model->getAttributeLabel('keywords'))
	->hint(Yii::t('app', 'Provide some keywords (separated by commas) that describe your website. These will be the default keywords that appear in the tag in your page header. Enter the most relevant keywords you can think of to help your website\'s search engine rankings.')); ?>

<div class="ln_solid"></div>

<?php $online = BaseSetting::getOnline();
echo $form->field($model, 'online', ['template' => '{beginLabel}{labelTitle}{hint}{endLabel}{beginWrapper}{input}{error}{endWrapper}'])
	->radioList($online)
	->label($model->getAttributeLabel('online'))
	->hint(Yii::t('app', 'Maintenance Mode will prevent site visitors from accessing your website.')); ?>

<div id="construction" <?php echo $model->online == '1' ? 'style="display: none;"' : ''; ?>>
	<?php $model->construction_date = !in_array($model->construction_date, array('0000-00-00','1970-01-01','0002-12-02','-0001-11-30')) ? $model->construction_date : '';
	echo $form->field($model, 'construction_date')
		->textInput(['type' => 'date'])
		->label($model->getAttributeLabel('construction_date')); ?>

	<?php $options = [];
	if($model->online != '2')
		$options = ArrayHelper::merge($options, ['style' => 'display: none;']);
	echo $form->field($model, 'construction_text[comingsoon]', ['options'=>$options])
		->textarea(['rows'=>4, 'cols'=>50])
		->label($model->getAttributeLabel('construction_text[comingsoon]')); ?>

	<?php $options = [];
	if($model->online != '0')
		$options = ArrayHelper::merge($options, ['style' => 'display: none;']);
	echo $form->field($model, 'construction_text[maintenance]', ['options'=>$options])
		->textarea(['rows'=>4, 'cols'=>50])
		->label($model->getAttributeLabel('construction_text[maintenance]')); ?>
</div>

<div class="ln_solid"></div>

<?php $appType = BaseSetting::getAnalytics();
echo $form->field($model, 'analytic')
	->dropDownList($appType, ['prompt'=>''])
	->label($model->getAttributeLabel('analytic'))
	->hint(Yii::t('app', 'Want to use Google Analytics to keep track of your site\'s traffic data? Setup is super easy. Just enter your Google Analytics Tracking ID and *bam*... you\'re tracking your site\'s traffic stats! If you need help finding your ID, check here.')); ?>

<?php echo $form->field($model, 'analytic_property')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('analytic_property'))
	->hint(Yii::t('app', 'Enter the Google Analytics Website Property (Tracking ID).')); ?>


<div class="ln_solid"></div>

<?php echo $form->field($model, 'backoffice_theme')
	->widget(Selectize::className(), [
		'cascade' => true,
		'items' => $themes,
		'pluginOptions' => [
			'onChange' => new \yii\web\JsExpression('function(value) {
				if (!value.length) return;
				basesetting_backoffice_theme_sublayout.disable();
				basesetting_backoffice_theme_sublayout.clearOptions();
				basesetting_backoffice_theme_sublayout.load(function(callback) {
					sublayout && sublayout.abort();
					sublayout = $.ajax({
						url: \''.$getSublayoutUrl.'\' + value,
						success: function(results) {
							basesetting_backoffice_theme_sublayout.removeOption(v_backend_sublayout);
							basesetting_backoffice_theme_sublayout.showInput();
							basesetting_backoffice_theme_sublayout.enable();
							callback(results);
						},
						error: function() {
							callback();
						}
					})
				});
				basesetting_backoffice_theme_pagination.disable();
				basesetting_backoffice_theme_pagination.clearOptions();
				basesetting_backoffice_theme_pagination.load(function(callback) {
					pagination && pagination.abort();
					pagination = $.ajax({
						url: \''.$getPaginationUrl.'\' + value,
						success: function(results) {
							basesetting_backoffice_theme_pagination.removeOption(v_backend_pagination);
							basesetting_backoffice_theme_pagination.showInput();
							basesetting_backoffice_theme_pagination.enable();
							callback(results);
						},
						error: function() {
							callback();
						}
					})
				});
			}'),
		],
	])
	->label($model->getAttributeLabel('backoffice_theme'));?>

<?php echo $form->field($model, 'backoffice_theme_sublayout')
	->widget(Selectize::className(), [
		'cascade' => true,
		'items' => $backSubLayout,
		'pluginOptions' => [
			'valueField' => 'id',
			'labelField' => 'label',
			'searchField' => ['label'],
			'onChange' => new \yii\web\JsExpression('function(value) {
				v_backend_sublayout = value;
			}'),
		],
	])
	->label($model->getAttributeLabel('backoffice_theme_sublayout'));?>

<?php echo $form->field($model, 'backoffice_theme_pagination')
	->widget(Selectize::className(), [
		'cascade' => true,
		'items' => $backPagination,
		'pluginOptions' => [
			'valueField' => 'id',
			'labelField' => 'label',
			'searchField' => ['label'],
			'onChange' => new \yii\web\JsExpression('function(value) {
				v_backend_pagination = value;
			}'),
		],
	])
	->label($model->getAttributeLabel('backoffice_theme_pagination'));?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'theme')
	->widget(Selectize::className(), [
		'cascade' => true,
		'items' => $themes,
		'pluginOptions' => [
			'onChange' => new \yii\web\JsExpression('function(value) {
				if (!value.length) return;
				basesetting_theme_sublayout.disable();
				basesetting_theme_sublayout.clearOptions();
				basesetting_theme_sublayout.load(function(callback) {
					sublayout && sublayout.abort();
					sublayout = $.ajax({
						url: \''.$getSublayoutUrl.'\' + value,
						success: function(results) {
							basesetting_theme_sublayout.removeOption(v_front_sublayout);
							basesetting_theme_sublayout.showInput();
							basesetting_theme_sublayout.enable();
							callback(results);
						},
						error: function() {
							callback();
						}
					})
				});
				basesetting_theme_pagination.disable();
				basesetting_theme_pagination.clearOptions();
				basesetting_theme_pagination.load(function(callback) {
					pagination && pagination.abort();
					pagination = $.ajax({
						url: \''.$getPaginationUrl.'\' + value,
						success: function(results) {
							basesetting_theme_pagination.removeOption(v_front_pagination);
							basesetting_theme_pagination.showInput();
							basesetting_theme_pagination.enable();
							callback(results);
						},
						error: function() {
							callback();
						}
					})
				});
			}'),
		],
	])
	->label($model->getAttributeLabel('theme'));?>

<?php echo $form->field($model, 'theme_sublayout')
	->widget(Selectize::className(), [
		'cascade' => true,
		'items' => $frontSubLayout,
		'pluginOptions' => [
			'valueField' => 'id',
			'labelField' => 'label',
			'searchField' => ['label'],
			'onChange' => new \yii\web\JsExpression('function(value) {
				v_front_sublayout = value;
			}'),
		],
	])
	->label($model->getAttributeLabel('theme_sublayout'));?>

<?php echo $form->field($model, 'theme_pagination')
	->widget(Selectize::className(), [
		'cascade' => true,
		'items' => $frontPagination,
		'pluginOptions' => [
			'valueField' => 'id',
			'labelField' => 'label',
			'searchField' => ['label'],
			'onChange' => new \yii\web\JsExpression('function(value) {
				v_front_pagination = value;
			}'),
		],
	])
	->label($model->getAttributeLabel('theme_pagination'));?>

<?php echo $form->field($model, 'theme_include_script', ['template' => '{beginLabel}{labelTitle}{hint}{endLabel}{beginWrapper}{input}{error}{endWrapper}'])
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('theme_include_script'))
	->hint(Yii::t('app', 'Anything entered into the box below will be included at the bottom of the <head> tag. If you want to include a script or stylesheet, be sure to use the &lt;script&gt; or &lt;link&gt; tag.')); ?>

<div class="ln_solid"></div>
<div class="form-group row">
	<div class="col-md-6 col-sm-9 col-xs-12 col-12 col-sm-offset-3">
		<?php echo Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>