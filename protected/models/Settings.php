<?php
/**
 * Settings
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 20 December 2018, 14:36 WIB
 * @link http://github.com/ommu/ommu
 *
 * This is the model class for table "ommu_settings".
 *
 * The followings are the available columns in table "ommu_settings":
 * @property integer $id
 * @property string $name
 * @property string $value
 * @property string $module_id
 * @property string $input_type
 * @property string $option_value
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 *
 * The followings are the available model relations:
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace app\models;

use Yii;
use ommu\users\models\Users;

class Settings extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['modified_date','modified_search'];

	// Search Variable
	public $creation_search;
	public $modified_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_settings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['name', 'value', 'module_id'], 'required'],
			[['creation_id', 'modified_id'], 'integer'],
			[['value'], 'string'],
			[['creation_date', 'modified_date'], 'safe'],
			[['name', 'module_id'], 'string', 'max' => 64],
			[['input_type'], 'string', 'max' => 50],
			[['option_value'], 'string', 'max' => 256],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'name' => Yii::t('app', 'Name'),
			'value' => Yii::t('app', 'Value'),
			'module_id' => Yii::t('app', 'Module'),
			'input_type' => Yii::t('app', 'Input Type'),
			'option_value' => Yii::t('app', 'Option Value'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'creation_search' => Yii::t('app', 'Creation'),
			'modified_search' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreation()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'creation_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getModified()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'modified_id']);
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
		$this->templateColumns['name'] = [
			'attribute' => 'name',
			'value' => function($model, $key, $index, $column) {
				return $model->name;
			},
		];
		$this->templateColumns['value'] = [
			'attribute' => 'value',
			'value' => function($model, $key, $index, $column) {
				return $model->value;
			},
		];
		$this->templateColumns['module_id'] = [
			'attribute' => 'module_id',
			'value' => function($model, $key, $index, $column) {
				return $model->module_id;
			},
		];
		$this->templateColumns['input_type'] = [
			'attribute' => 'input_type',
			'value' => function($model, $key, $index, $column) {
				return $model->input_type;
			},
		];
		$this->templateColumns['option_value'] = [
			'attribute' => 'option_value',
			'value' => function($model, $key, $index, $column) {
				return $model->option_value;
			},
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		if(!Yii::$app->request->get('creation')) {
			$this->templateColumns['creation_search'] = [
				'attribute' => 'creation_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->creation) ? $model->creation->displayname : '-';
				},
			];
		}
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
		if(!Yii::$app->request->get('modified')) {
			$this->templateColumns['modified_search'] = [
				'attribute' => 'modified_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->modified) ? $model->modified->displayname : '-';
				},
			];
		}
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
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			else
				$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
		}
		return true;
	}
}
