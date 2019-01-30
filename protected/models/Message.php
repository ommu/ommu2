<?php
/**
 * Message
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 15 September 2017, 19:16 WIB
 * @modified date 22 April 2017, 18:28 WIB
 * @link https://github.com/ommu/ommu
 *
 * This is the model class for table "message".
 *
 * The followings are the available columns in table "message":
 * @property integer $id
 * @property string $language
 * @property string $translation
 *
 * The followings are the available model relations:
 * @property SourceMessage $phrase
 *
 */

namespace app\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;

class Message extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	// Search Variable
	public $phrase_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'message';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['id', 'language', 'translation'], 'required'],
			[['id'], 'integer'],
			[['translation', 'phrase_search'], 'string'],
			[['language'], 'string', 'max' => 16],
			[['id', 'language'], 'unique', 'targetAttribute' => ['id', 'language']],
			[['id'], 'exist', 'skipOnError' => true, 'targetClass' => SourceMessage::className(), 'targetAttribute' => ['id' => 'id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'language' => Yii::t('app', 'Language'),
			'translation' => Yii::t('app', 'Translation'),
			'phrase_search' => Yii::t('app', 'Phrase'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPhrase()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'id']);
	}

	/**
	 * @inheritdoc
	 * @return \app\models\query\MessageQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new \app\models\query\MessageQuery(get_called_class());
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
		if(!Yii::$app->request->get('phrase')) {
			$this->templateColumns['phrase_search'] = [
				'attribute' => 'phrase_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->phrase) ? $model->phrase->message : '-';
				},
				'format' => 'html',
			];
		}
		$this->templateColumns['language'] = [
			'attribute' => 'language',
			'filter' => CoreLanguages::getLanguage(true, 'code'),
			'value' => function($model, $key, $index, $column) {
				return $model->language;
			},
		];
		$this->templateColumns['translation'] = [
			'attribute' => 'translation',
			'value' => function($model, $key, $index, $column) {
				return $model->translation;
			},
			'format' => 'html',
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
}
