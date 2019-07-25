<?php
/**
 * @var $this app\components\View
 * @var $this app\controllers\SettingController
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 25 July 2019, 18:32 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\components\widgets\ActiveForm;
use ommu\selectize\Selectize;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="base-setting-form">

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php $googleMeta = $model::getEnabled();
echo $form->field($model, 'google_meta')
	->dropDownList($googleMeta)
	->label($model->getAttributeLabel('google_meta')); ?>

<?php echo $form->field($model, 'facebook_meta')
	->dropDownList($googleMeta)
	->label($model->getAttributeLabel('facebook_meta')); ?>

<?php echo $form->field($model, 'twitter_meta')
	->dropDownList($googleMeta)
	->label($model->getAttributeLabel('twitter_meta')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'submitButton')
	->submitButton(['button'=>Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary'])]); ?>

<?php ActiveForm::end(); ?>

</div>