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
use app\components\ActiveForm;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="base-setting-form">

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php // echo $form->errorSummary($model);?>

<?php $nameSmall = $form->field($model, 'name[small]', ['template' => '<div class="h5">Small Name</div>{input}', 'options' => ['tag' => null]])
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('name')); ?>

<?php echo $form->field($model, 'name[long]', ['template' => '{beginLabel}{labelTitle}{hint}{endLabel}{beginWrapper}'.$nameSmall.'<div class="h5">Long Name</div>{input}{error}{endWrapper}'])
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('name'))
	->hint(Yii::t('app', 'Give your website a unique name. This will appear in the &lt;title&gt; tag throughout most of your site.')); ?>

<?php echo $form->field($model, 'pagetitle_template', ['template' => '{beginLabel}{labelTitle}{hint}{endLabel}{beginWrapper}{input}{error}{endWrapper}'])
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

<?php echo $form->field($model, 'backoffice_theme')
	->dropDownList($themes, ['prompt'=>''])
	->label($model->getAttributeLabel('backoffice_theme'));?>

<?php echo $form->field($model, 'theme')
	->dropDownList($themes, ['prompt'=>''])
	->label($model->getAttributeLabel('theme'));?>

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