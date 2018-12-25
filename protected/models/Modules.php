<?php
/**
 * Modules
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 24 December 2017, 20:11 WIB
 * @link http://github.com/ommu/ommu
 *
 * This is the model class for table "ommu_core_modules".
 *
 * The followings are the available columns in table "ommu_core_modules":
 * @property integer $id
 * @property string $module_id
 * @property integer $enabled
 *
 */

namespace app\models;

use Yii;

class Modules extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;
	
	public $gridForbiddenColumn = [];

	const CACHE_ENABLE_MODULE_IDS = 'enabledModuleIds';

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_core_modules';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['module_id'], 'required'],
			[['enabled'], 'integer'],
			[['module_id'], 'string', 'max' => 64],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'module_id' => Yii::t('app', 'Module'),
			'enabled' => Yii::t('app', 'Enabled'),
		];
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

		$this->templateColumns['_no'] = [
			'header' => Yii::t('app', 'No'),
			'class'  => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['module_id'] = [
			'attribute' => 'module_id',
			'value' => function($model, $key, $index, $column) {
				return $model->module_id;
			},
		];
		$this->templateColumns['enabled'] = [
			'attribute' => 'enabled',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->enabled);
			},
			'contentOptions' => ['class'=>'center'],
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find()
				->select([$column])
				->where(['id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * Mengembalikan daftar module yang dalam kondisi aktif.
	 *
	 * @return array
	 */
	public static function getEnableIds() {
		$enabledModules = Yii::$app->cache->get(self::CACHE_ENABLE_MODULE_IDS);
		if($enabledModules === false) {
			$enabledModules = [];
			foreach(self::find()
				->andWhere(['enabled' => '1'])
				->all() as $em) {
				$enabledModules[] = $em->module_id;
			}
			Yii::$app->cache->set(self::CACHE_ENABLE_MODULE_IDS, $enabledModules);
		}
		return $enabledModules;
	}

	/**
	 * After save attributes
	 */
	public function afterSave($insert, $changedAttributes)
	{
		Yii::$app->cache->delete(self::CACHE_ENABLE_MODULE_IDS);
		parent::afterSave($insert, $changedAttributes);
	}

	/**
	 * After delete attributes
	 */
	public function afterDelete()
	{
		Yii::$app->cache->delete(self::CACHE_ENABLE_MODULE_IDS);
		parent::afterDelete();
	}
}
