<?php
/**
 * BaseSetting
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 22 April 2019, 23:14 WIB
 * @link https://github.com/ommu/ommu
 *
 * Kelas ini untuk mennghandle pengaturan pada base module.
 *
 */

namespace app\models;

use Yii;

class BaseSetting extends \yii\base\Model
{
	public $app;
	public $app_type;
	public $online;
	public $name;
	public $description;
	public $keywords;
	public $pagetitle_template;
	public $backoffice_theme;
	public $backoffice_theme_sublayout;
	public $theme;
	public $theme_sublayout;
	public $theme_include_script;
	public $construction_date;
	public $construction_text;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['app_type', 'name', 'online'], 'required'],
			[['online'], 'integer'],
			[['app_type', 'name', 'description', 'keywords', 'pagetitle_template', 'backoffice_theme', 'backoffice_theme_sublayout', 'theme', 'theme_sublayout', 'theme_include_script', 'construction_date', 'construction_text'], 'string'],
			[['description', 'keywords', 'pagetitle_template', 'backoffice_theme', 'backoffice_theme_sublayout', 'theme', 'theme_sublayout', 'theme_include_script', 'construction_date', 'construction_text'], 'safe'],
			[['app_type'], 'string', 'max' => 16],
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
			'app_type' => Yii::t('app', 'Site Type'),
			'online' => Yii::t('app', 'Maintenance Mode'),
			'name' => Yii::t('app', 'Site Title'),
			'description' => Yii::t('app', 'Site Description'),
			'keywords' => Yii::t('app', 'Site Keyword'),
			'pagetitle_template' => Yii::t('app', 'Page Title Template'),
			'backoffice_theme' => Yii::t('app', 'Backend Theme'),
			'backoffice_theme_sublayout' => Yii::t('app', 'Backend Sublayout'),
			'theme'=> Yii::t('app', 'Frontend Theme'),
			'theme_sublayout'=> Yii::t('app', 'Frontend Sublayout'),
			'theme_include_script'=> Yii::t('app', 'Head Scripts/Styles'),
			'construction_date' => Yii::t('app', 'Offline Date'),
			'construction_text' => Yii::t('app', 'Maintenance Text'),
			'construction_text[comingsoon]' => Yii::t('app', 'Coming Soon Text'),
			'construction_text[maintenance]' => Yii::t('app', 'Maintenance Text'),
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		parent::init();

		$this->app_type = Yii::$app->setting->get($this->getId('app_type'), 'company');
		$this->online = Yii::$app->setting->get($this->getId('online'), 1);
		$this->name = unserialize(Yii::$app->setting->get($this->getId('name')));
		$this->description = Yii::$app->setting->get($this->getId('description'));
		$this->keywords = Yii::$app->setting->get($this->getId('keywords'));
		$this->pagetitle_template = Yii::$app->setting->get($this->getId('pagetitle_template'), '{title} | {small-name} - {long-name}');
		$this->backoffice_theme = Yii::$app->setting->get($this->getId('backoffice_theme'));
		$this->backoffice_theme_sublayout = Yii::$app->setting->get($this->getId('backoffice_theme_sublayout'));
		$this->theme = Yii::$app->setting->get($this->getId('theme'));
		$this->theme_sublayout = Yii::$app->setting->get($this->getId('theme_sublayout'));
		$this->theme_include_script = Yii::$app->setting->get($this->getId('theme_include_script'));
		$this->construction_date = Yii::$app->setting->get($this->getId('construction_date'));
		$this->construction_text = unserialize(Yii::$app->setting->get($this->getId('construction_text')));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getId($name)
	{
		return join('_', [$this->app, $name]);
	}

	/**
	 * function getAppType
	 */
	public static function getAppType($value=null)
	{
		$items = array(
			'community' => Yii::t('app', 'Social Media / Community Website'),
			'company' => Yii::t('app', 'Company Profile'),
			'demo' => Yii::t('app', 'Demo Application'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * function getOnline
	 */
	public static function getOnline($value=null)
	{
		$items = array(
			'1' => Yii::t('app', 'Online'),
			'2' => Yii::t('app', 'Coming Soon'),
			'0' => Yii::t('app', 'Maintenance'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * {@inheritdoc}
	 */
	public function langMap($lang, $rev = false)
	{
		$maps = ['id' => 'id-ID', 'en' => 'en-US'];
		if ($rev) {
			$res = '';
			foreach ($maps as $key => $val) {
				if ($lang == $val) {
					$res = $key;
					break;
				}
			}
			return $res;
		}

		if (!array_key_exists($lang, $maps)) {
			throw new \Exception(strtr('":lang" tidak ada!.', [':lang' => $lang]));
		}
		return $maps[$lang];
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if($this->app_type == '')
			$this->addError('app_type', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('app_type')]));

		if($this->name['small'] == '' || $this->name['long'] == '')
			$this->addError('name', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('name')]));

		if($this->online == '')
			$this->addError('online', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('online')]));

		if($this->online != 1) {
			if($this->construction_date == '')
				$this->addError('construction_date', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('construction_date')]));
			if($this->online == 0 && $this->construction_text['maintenance'] == '')
				$this->addError('construction_text', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('construction_text[maintenance]')]));
			if($this->online == 2 && $this->construction_text['comingsoon'] == '')
				$this->addError('construction_text', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('construction_text[comingsoon]')]));
		}

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

		$this->name = serialize($this->name);
		$this->construction_date = Yii::$app->formatter->asDate($this->construction_date, 'php:Y-m-d');
		$this->construction_text = serialize($this->construction_text);

		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function save()
	{
		if(!$this->beforeSave())
			return false;

		Yii::$app->setting->set($this->getId('app_type'), $this->app_type);
		Yii::$app->setting->set($this->getId('online'), $this->online);
		Yii::$app->setting->set($this->getId('name'), $this->name);
		Yii::$app->setting->set($this->getId('description'), $this->description);
		Yii::$app->setting->set($this->getId('keywords'), $this->keywords);
		Yii::$app->setting->set($this->getId('pagetitle_template'), $this->pagetitle_template);
		Yii::$app->setting->set($this->getId('backoffice_theme'), $this->backoffice_theme);
		Yii::$app->setting->set($this->getId('backoffice_theme_sublayout'), $this->backoffice_theme_sublayout);
		Yii::$app->setting->set($this->getId('theme'), $this->theme);
		Yii::$app->setting->set($this->getId('theme_sublayout'), $this->theme_sublayout);
		Yii::$app->setting->set($this->getId('theme_include_script'), $this->theme_include_script);
		Yii::$app->setting->set($this->getId('construction_date'), $this->construction_date);
		Yii::$app->setting->set($this->getId('construction_text'), $this->construction_text);
		
		return true;
	}
}