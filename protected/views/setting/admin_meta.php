<?php
/**
 * @var $this app\components\View
 * @var $this app\controllers\SettingController
 *
 * @author Putra Sudaryanto <putra@ommu.co>
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
use yii\web\JsExpression;
use ommu\core\models\CoreZoneVillage;
use ommu\core\models\CoreZoneDistrict;
use ommu\core\models\CoreZoneCity;
use ommu\core\models\CoreZoneProvince;
use ommu\core\models\CoreZoneCountry;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$country = $model->office_address['country'];
$province = $model->office_address['province'];
$city = $model->office_address['city'];
$district = $model->office_address['district'];
$village = $model->office_address['village'];

$js = <<<JS
	var country, province, city, district, village;
	var v_country = '$country';
	var v_province = '$province';
	var v_city = '$city';
	var v_district = '$district';
	var v_village = '$village';
JS;
	$this->registerJs($js, \yii\web\View::POS_END);
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

<?php echo $form->field($model, 'office_location')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('office_location'))
	->hint(Yii::t('app', 'A struct containing metadata defining the location of a place')); ?>

<?php echo $form->field($model, 'office_name')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('office_name')); ?>

<?php
$villageSuggestUrl = Url::to(['/admin/zone/village/suggest', 'extend'=>'village_name,zipcode,district_name,city_id,province_id,country_id']);
$districtSuggestUrl = Url::to(['/admin/zone/district/suggest', 'extend'=>'district_name,city_id,province_id,country_id']);
$citySuggestUrl = Url::to(['/admin/zone/city/suggest', 'extend'=>'city_name,province_id,country_id']);
$provinceSuggestUrl = Url::to(['/admin/zone/province/suggest', 'extend'=>'country_id']);
$countrySuggestUrl = Url::to(['/admin/zone/country/suggest']);

$officeAddressVillage = $form->field($model, 'office_address[village]', ['template' => '{beginWrapper}{input}{endWrapper}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-4 col-xs-6 col-sm-offset-3 mt-3'], 'options' => ['tag' => null]])
	->widget(Selectize::className(), [
		'cascade' => true,
		'options' => [
			'placeholder' => Yii::t('app', 'Select a village..'),
			'class' => 'form-control contacts',
		],
		'items' => ArrayHelper::merge([''=>Yii::t('app', 'Select a village..')], []),
		'url' => $villageSuggestUrl,
		'queryParam' => 'term',
		'pluginOptions' => [
			'valueField' => 'village_name',
			'labelField' => 'label',
			'searchField' => ['label'],
			'searchConjunction' => 'or',
			'sortField' => ['label'],
			'render' => [
				'item' => new JsExpression('function(item, escape) {
					return \'<div class="item">\' + escape(item.village_name) + \'</div>\';
				}'),
				'option' => new JsExpression('function(item, escape) {
					var label = item.village_name || item.label;
					var caption = item.village_name ? item.label : null;
					return \'<div>\' +
						\'<span class="label">\' + escape(label) + \'</span>\' +
						(caption ? \'<span class="caption">\' + escape(caption) + \'</span>\' : \'\') +
					\'</div>\';
				}'),
			],
			'onChange' => new JsExpression('function(value) {
				v_village = value;
				if (!value.length) return;
				var options = this.options;
				var selected = this.options[value];
				if(selected.zipcode)
					$(\'form\').find(\'#metasetting-office_address-zipcode\').val(selected.zipcode);
				if(selected.district_name) {
					metasetting_office_address_district.addOption({district_name: selected.district_name, label: selected.district_name});
					metasetting_office_address_district.setValue(selected.district_name);
				}
				if(selected.city_id)
					metasetting_office_address_city.setValue(selected.city_id);
				if(selected.province_id)
					metasetting_office_address_province.setValue(selected.province_id);
				if(selected.country_id)
					metasetting_office_address_country.setValue(selected.country_id);
			}'),
		],
	])
	->label($model->getAttributeLabel('office_address[village]'));
if($model->office_address['village']) {
$js = <<<JS
	metasetting_office_address_village.addOption({label: '{$model->office_address['village']}', village_name: '{$model->office_address['village']}'});
	metasetting_office_address_village.setValue('{$model->office_address['village']}');
JS;
	$this->registerJs($js, \yii\web\View::POS_END);
} ?>

<?php $officeAddressDistrict = $form->field($model, 'office_address[district]', ['template' => '{beginWrapper}{input}{endWrapper}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-5 col-xs-6 mt-3'], 'options' => ['tag' => null]])
	->widget(Selectize::className(), [
		'cascade' => true,
		'options' => [
			'placeholder' => Yii::t('app', 'Select a district..'),
			'class' => 'form-control contacts',
		],
		'items' => ArrayHelper::merge([''=>Yii::t('app', 'Select a district..')], []),
		'url' => $districtSuggestUrl,
		'queryParam' => 'term',
		'pluginOptions' => [
			'valueField' => 'district_name',
			'labelField' => 'label',
			'searchField' => ['label'],
			'searchConjunction' => 'or',
			'sortField' => ['label'],
			'render' => [
				'item' => new JsExpression('function(item, escape) {
					return \'<div class="item">\' + escape(item.district_name) + \'</div>\';
				}'),
				'option' => new JsExpression('function(item, escape) {
					var label = item.district_name || item.label;
					var caption = item.district_name ? item.label : null;
					return \'<div>\' +
						\'<span class="label">\' + escape(label) + \'</span>\' +
						(caption ? \'<span class="caption">\' + escape(caption) + \'</span>\' : \'\') +
					\'</div>\';
				}'),
			],
			'onChange' => new JsExpression('function(value) {
				v_district = value;
				if (!value.length) return;
				var options = this.options;
				var selected = this.options[value];
				if(selected.city_id)
					metasetting_office_address_city.setValue(selected.city_id);
				if(selected.province_id)
					metasetting_office_address_province.setValue(selected.province_id);
				if(selected.country_id)
					metasetting_office_address_country.setValue(selected.country_id);
				// metasetting_office_address_village.disable(); 
				// metasetting_office_address_village.clearOptions();
				// metasetting_office_address_village.load(function(callback) {
				// 	district && district.abort();
				// 	district = $.ajax({
				// 		url: \''.$villageSuggestUrl.'\',
				// 		data: {\'did\': value},
				// 		success: function(results) {
				// 			metasetting_office_address_village.removeOption(v_village);
				// 			metasetting_office_address_village.showInput();
				// 			metasetting_office_address_village.enable();
				// 			callback(results);
				// 		},
				// 		error: function() {
				// 			callback();
				// 		}
				// 	})
				// });
			}'),
		],
	])
	->label($model->getAttributeLabel('office_address[district]'));
if($model->office_address['district']) {
$js = <<<JS
	metasetting_office_address_district.addOption({label: '{$model->office_address['district']}', district_name: '{$model->office_address['district']}'});
	metasetting_office_address_district.setValue('{$model->office_address['district']}');
JS;
	$this->registerJs($js, \yii\web\View::POS_END);
} ?>

<?php echo $form->field($model, 'office_address[place]', ['template' => '{label}{beginWrapper}{input}{endWrapper}'.$officeAddressVillage.$officeAddressDistrict.'{error}{hint}', 'horizontalCssClasses' => ['error'=>'col-sm-6 col-xs-12 col-sm-offset-3', 'hint'=>'col-sm-6 col-xs-12 col-sm-offset-3']])
	->textarea(['rows'=>3, 'cols'=>50, 'maxlength'=>64, 'placeholder'=>$model->getAttributeLabel('office_address[place]')])
	->label($model->getAttributeLabel('office_address'))
	->hint(Yii::t('app', 'The number, street, district and village of the postal address for this business')); ?>

<?php echo $form->field($model, 'office_address[city]')
	->widget(Selectize::className(), [
		'cascade' => true,
		'options' => [
			'placeholder' => Yii::t('app', 'Select a city..'),
		],
		'items' => ArrayHelper::merge([''=>Yii::t('app', 'Select a city..')], CoreZoneCity::getCity()),
		// 'items' => ArrayHelper::merge([''=>Yii::t('app', 'Select a city..')], []),
		// 'url' => $citySuggestUrl,
		// 'queryParam' => 'term',
		'pluginOptions' => [
			'valueField' => 'id',
			'labelField' => 'label',
			'searchField' => ['label'],
			'sortField' => ['label'],
			'onChange' => new JsExpression('function(value) {
				v_city = value;
				if (!value.length) return;
				var options = this.options;
				var selected = this.options[value];
				if(selected.province_id)
					metasetting_office_address_province.setValue(selected.province_id);
				if(selected.country_id)
					metasetting_office_address_country.setValue(selected.country_id);
				// metasetting_office_address_district.disable(); 
				// metasetting_office_address_district.clearOptions();
				// metasetting_office_address_district.load(function(callback) {
				// 	city && city.abort();
				// 	city = $.ajax({
				// 		url: \''.$districtSuggestUrl.'\',
				// 		data: {\'cid\': value},
				// 		success: function(results) {
				// 			metasetting_office_address_district.removeOption(v_district);
				// 			metasetting_office_address_district.showInput();
				// 			metasetting_office_address_district.enable();
				// 			callback(results);
				// 		},
				// 		error: function() {
				// 			callback();
				// 		}
				// 	})
				// });
			}'),
		],
	])
	->label($model->getAttributeLabel('office_address[city]'))
	->hint(Yii::t('app', 'The city (or locality) line of the postal address for this business')); ?>

<?php echo $form->field($model, 'office_address[province]')
	->widget(Selectize::className(), [
		'cascade' => true,
		'options' => [
			'placeholder' => Yii::t('app', 'Select a province..'),
		],
		'items' => ArrayHelper::merge([''=>Yii::t('app', 'Select a province..')], CoreZoneProvince::getProvince()),
		// 'items' => ArrayHelper::merge([''=>Yii::t('app', 'Select a province..')], []),
		// 'url' => $provinceSuggestUrl,
		// 'queryParam' => 'term',
		'pluginOptions' => [
			'valueField' => 'id',
			'labelField' => 'label',
			'searchField' => ['label'],
			'sortField' => ['label'],
			'onChange' => new JsExpression('function(value) {
				v_province = value;
				if (!value.length) return;
				var options = this.options;
				var selected = this.options[value];
				if(selected.country_id)
					metasetting_office_address_country.setValue(selected.country_id);
				// metasetting_office_address_city.disable(); 
				// metasetting_office_address_city.clearOptions();
				// metasetting_office_address_city.load(function(callback) {
				// 	province && province.abort();
				// 	province = $.ajax({
				// 		url: \''.$citySuggestUrl.'\',
				// 		data: {\'pid\': value},
				// 		success: function(results) {
				// 			metasetting_office_address_city.removeOption(v_city);
				// 			metasetting_office_address_city.showInput();
				// 			metasetting_office_address_city.enable();
				// 			callback(results);
				// 		},
				// 		error: function() {
				// 			callback();
				// 		}
				// 	})
				// });
			}'),
		],
	])
	->label($model->getAttributeLabel('office_address[province]')); ?>

<?php echo $form->field($model, 'office_address[zipcode]')
	->textInput(['maxlength'=>6])
	->label($model->getAttributeLabel('office_address[zipcode]'))
	->hint(Yii::t('app', 'The state (or region) line of the postal address for this business')); ?>

<?php echo $form->field($model, 'office_address[country]')
	->widget(Selectize::className(), [
		'cascade' => true,
		'options' => [
			'placeholder' => Yii::t('app', 'Select a country..'),
		],
		'items' => ArrayHelper::merge([''=>Yii::t('app', 'Select a country..')], CoreZoneCountry::getCountry()),
		// 'items' => ArrayHelper::merge([''=>Yii::t('app', 'Select a country..')], []),
		// 'url' => $countrySuggestUrl,
		// 'queryParam' => 'term',
		'pluginOptions' => [
			'valueField' => 'id',
			'labelField' => 'label',
			'searchField' => ['label'],
			'sortField' => ['label'],
			'onChange' => new JsExpression('function(value) {
				v_country = value;
				if (!value.length) return;
				// metasetting_office_address_province.disable(); 
				// metasetting_office_address_province.clearOptions();
				// metasetting_office_address_province.load(function(callback) {
				// 	country && country.abort();
				// 	country = $.ajax({
				// 		url: \''.$provinceSuggestUrl.'\',
				// 		data: {\'cid\': value},
				// 		success: function(results) {
				// 			metasetting_office_address_province.removeOption(v_province);
				// 			metasetting_office_address_province.showInput();
				// 			metasetting_office_address_province.enable();
				// 			callback(results);
				// 		},
				// 		error: function() {
				// 			callback();
				// 		}
				// 	})
				// });
			}'),
		],
	])
	->label($model->getAttributeLabel('office_address[country]')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'office_contact[phone]')
	->textarea(['rows'=>3, 'cols'=>50])
	->label($model->getAttributeLabel('office_contact[phone]'))
	->hint(Yii::t('app', 'A telephone number to contact this business')); ?>

<?php echo $form->field($model, 'office_contact[fax]')
	->textInput()
	->label($model->getAttributeLabel('office_contact[fax]'))
	->hint(Yii::t('app', 'A fax number to contact this business')); ?>

<?php echo $form->field($model, 'office_contact[hotline]')
	->textarea(['rows'=>3, 'cols'=>50])
	->label($model->getAttributeLabel('office_contact[hotline]')); ?>

<?php echo $form->field($model, 'office_contact[email]')
	->textInput()
	->label($model->getAttributeLabel('office_contact[email]'))
	->hint(Yii::t('app', 'An email address to contact this business')); ?>

<?php echo $form->field($model, 'office_contact[website]')
	->textInput()
	->label($model->getAttributeLabel('office_contact[website]'))
	->hint(Yii::t('app', 'A website for this business')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'submitButton')
	->submitButton(['button'=>Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary'])]); ?>

<?php ActiveForm::end(); ?>

</div>