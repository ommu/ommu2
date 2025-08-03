<?php
/**
 * MetaSetting
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 25 July 2019, 18:32 WIB
 * @link https://github.com/ommu/ommu2
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
	public $office_name;
	public $office_location;
	public $office_address;
	public $office_contact;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['app_type', 'google_meta', 'facebook_meta', 'twitter_meta'], 'required'],
			[['google_meta', 'facebook_meta', 'twitter_meta'], 'integer'],
			[['office_name', 'office_location'], 'string'],
			[['office_address', 'office_contact'], 'safe'],
			[['office_name', 'office_location'], 'string', 'max' => 64],
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
			'office_name' => Yii::t('app', 'Office Name'),
			'office_location' => Yii::t('app', 'Office Maps Location'),
			'office_address' => Yii::t('app', 'Office Address'),
			'office_address[place]' => Yii::t('app', 'Place'),
			'office_address[country]' => Yii::t('app', 'Country'),
			'office_address[province]' => Yii::t('app', 'Province'),
			'office_address[city]' => Yii::t('app', 'City'),
			'office_address[district]' => Yii::t('app', 'District'),
			'office_address[village]' => Yii::t('app', 'Village'),
			'office_address[zipcode]' => Yii::t('app', 'Zipcode'),
			'office_contact' => Yii::t('app', 'Office Contact'),
			'office_contact[phone]' => Yii::t('app', 'Phone'),
			'office_contact[fax]' => Yii::t('app', 'FAX'),
			'office_contact[email]' => Yii::t('app', 'Email'),
			'office_contact[hotline]' => Yii::t('app', 'Hotline'),
			'office_contact[website]' => Yii::t('app', 'Website'),
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
		$this->office_name = Yii::$app->meta->get($this->getId('office_name'));
		$this->office_location = Yii::$app->meta->get($this->getId('office_location'));
		$this->office_address = unserialize(Yii::$app->meta->get($this->getId('office_address')));
		$this->office_contact = unserialize(Yii::$app->meta->get($this->getId('office_contact')));
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

		if ($value !== null) {
			return $items[$value];
        } else {
			return $items;
        }
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{

		if (!empty($this->getErrors())) {
			return false;
        }

		return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave()
	{
		if (!$this->beforeValidate()) {
			return false;
        }

		$this->office_address = serialize($this->office_address);
		$this->office_contact = serialize($this->office_contact);

		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function save()
	{
		if (!$this->beforeSave()) {
			return false;
        }

		Yii::$app->meta->set($this->getId('google_meta'), $this->google_meta);
		Yii::$app->meta->set($this->getId('facebook_meta'), $this->facebook_meta);
		Yii::$app->meta->set($this->getId('twitter_meta'), $this->twitter_meta);
		Yii::$app->meta->set($this->getId('office_name'), $this->office_name);
		Yii::$app->meta->set($this->getId('office_location'), $this->office_location);
		Yii::$app->meta->set($this->getId('office_address'), $this->office_address);
		Yii::$app->meta->set($this->getId('office_contact'), $this->office_contact);
		
		return true;
	}
}