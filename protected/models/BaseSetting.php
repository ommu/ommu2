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
	public $name;
	public $description;
	public $keywords;
	public $pagetitle_template;
	public $backoffice_theme;
	public $backoffice_theme_sublayout;
	public $theme;
	public $theme_sublayout;
	public $theme_include_script;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['name'], 'required'],
			[['pagetitle_template'], 'string', 'max' => 64],
			[['name', 'description', 'keywords'], 'string', 'max' => 256],
			[['description', 'keywords', 'pagetitle_template', 'backoffice_theme', 'backoffice_theme_sublayout',
				'theme', 'theme_sublayout', 'theme_include_script'], 'safe'
			],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'app' => Yii::t('app', 'Application ID'),
			'name' => Yii::t('app', 'Site Title'),
			'description' => Yii::t('app', 'Site Description'),
			'keywords' => Yii::t('app', 'Site Keyword'),
			'pagetitle_template' => Yii::t('app', 'Page Title Template'),
			'backoffice_theme' => Yii::t('app', 'Backend Theme'),
			'backoffice_theme_sublayout' => Yii::t('app', 'Backend Sublayout'),
			'theme'=> Yii::t('app', 'Frontend Theme'),
			'theme_sublayout'=> Yii::t('app', 'Frontend Sublayout'),
			'theme_include_script'=> Yii::t('app', 'Head Scripts/Styles'),
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		parent::init();

		$this->name = unserialize(Yii::$app->setting->get($this->getId('name')));
		$this->description = Yii::$app->setting->get($this->getId('description'));
		$this->keywords = Yii::$app->setting->get($this->getId('keywords'));
		$this->pagetitle_template = Yii::$app->setting->get($this->getId('pagetitle_template'), '{title} | {small-name} - {long-name}');
		$this->backoffice_theme = Yii::$app->setting->get($this->getId('backoffice_theme'));
		$this->backoffice_theme_sublayout = Yii::$app->setting->get($this->getId('backoffice_theme_sublayout'));
		$this->theme = Yii::$app->setting->get($this->getId('theme'));
		$this->theme_sublayout = Yii::$app->setting->get($this->getId('theme_sublayout'));
		$this->theme_include_script = Yii::$app->setting->get($this->getId('theme_include_script'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getId($name)
	{
		return join('_', [$this->app, $name]);
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
	 * {@inheritdoc}
	 */
	public function save()
	{
		Yii::$app->setting->set($this->getId('name'), serialize($this->name));
		Yii::$app->setting->set($this->getId('description'), $this->description);
		Yii::$app->setting->set($this->getId('keywords'), $this->keywords);
		Yii::$app->setting->set($this->getId('pagetitle_template'), $this->pagetitle_template);
		Yii::$app->setting->set($this->getId('backoffice_theme'), $this->backoffice_theme);
		Yii::$app->setting->set($this->getId('backoffice_theme_sublayout'), $this->backoffice_theme_sublayout);
		Yii::$app->setting->set($this->getId('theme'), $this->theme);
		Yii::$app->setting->set($this->getId('theme_sublayout'), $this->theme_sublayout);
		Yii::$app->setting->set($this->getId('theme_include_script'), $this->theme_include_script);
		
		return true;
	}
}