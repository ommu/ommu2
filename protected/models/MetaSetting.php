<?php
/**
 * MetaSetting
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 25 July 2019, 18:32 WIB
 * @link https://github.com/ommu/ommu
 *
 * Kelas ini untuk mennghandle pengaturan pada base module.
 *
 */

namespace app\models;

use Yii;

class MetaSetting extends \yii\base\Model
{
	public $app;
	public $google_meta;
	public $facebook_meta;
	public $twitter_meta;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['app_type', 'google_meta', 'facebook_meta', 'twitter_meta'], 'required'],
			[['google_meta', 'facebook_meta', 'twitter_meta'], 'integer'],
			[[], 'string'],
			[[], 'safe'],
			[['app_type', 'analytic_property', 'logo'], 'string', 'max' => 16],
			[['pagetitle_template'], 'string', 'max' => 64],
			[['name', 'description', 'keywords'], 'string', 'max' => 256],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'app' => Yii::t('app', 'Application ID'),
			'google_meta' => Yii::t('app', 'Google Meta'),
			'facebook_meta' => Yii::t('app', 'Facebook Meta'),
			'twitter_meta' => Yii::t('app', 'Twitter Meta'),
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		parent::init();

		$this->google_meta = Yii::$app->meta->get($this->getId('google_meta'), 1);
		$this->facebook_meta = Yii::$app->meta->get($this->getId('facebook_meta'), 1);
		$this->twitter_meta = Yii::$app->meta->get($this->getId('twitter_meta'), 1);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getId($name)
	{
		return join('_', [$this->app, $name]);
	}

	/**
	 * function getAnalytics
	 */
	public static function getEnabled($value=null)
	{
		$items = array(
			'1' => Yii::t('app', 'Enable'),
			'0' => Yii::t('app', 'Disable'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{

		if(!empty($this->getErrors()))
			return false;

		return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave()
	{
		if(!$this->beforeValidate())
			return false;

		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function save()
	{
		if(!$this->beforeSave())
			return false;

		Yii::$app->meta->set($this->getId('google_meta'), $this->google_meta);
		Yii::$app->meta->set($this->getId('facebook_meta'), $this->facebook_meta);
		Yii::$app->meta->set($this->getId('twitter_meta'), $this->twitter_meta);
		
		return true;
	}
}