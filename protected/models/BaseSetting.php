<?php
/**
 * BaseSetting
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 22 April 2019, 23:14 WIB
 * @link https://github.com/ommu/ommu
 *
 * Kelas ini untuk mennghandle pengaturan pada base module.
 *
 */

namespace app\models;

use Yii;
use yii\web\UploadedFile;

class BaseSetting extends \yii\base\Model
{
	use \ommu\traits\FileTrait;

	public $app;
	public $app_type;
	public $online;
	public $name;
	public $description;
	public $keywords;
	public $logo;
	public $copyright;
	public $pagetitle_template;
	public $backoffice_theme;
	public $backoffice_theme_sublayout;
	public $backoffice_theme_pagination;
	public $backoffice_theme_loginlayout;
	public $backoffice_indexing;
	public $maintenance_theme;
	public $maintenance_theme_sublayout;
	public $maintenance_indexing;
	public $theme;
	public $theme_sublayout;
	public $theme_pagination;
	public $theme_loginlayout;
	public $theme_indexing;
	public $theme_include_script;
	public $construction_date;
	public $construction_text;
	public $analytic;
	public $analytic_property;
	public $old_logo;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['app_type', 'name', 'online'], 'required'],
			[['online', 'analytic', 'backoffice_indexing', 'maintenance_indexing', 'theme_indexing'], 'integer'],
			[['app_type', 'name', 'description', 'keywords', 'logo', 'copyright', 'pagetitle_template', 'backoffice_theme', 'backoffice_theme_sublayout', 'backoffice_theme_pagination', 'backoffice_theme_loginlayout', 'maintenance_theme', 'maintenance_theme_sublayout', 'theme', 'theme_sublayout', 'theme_pagination', 'theme_loginlayout', 'theme_include_script', 'construction_date', 'construction_text', 'analytic_property'], 'string'],
			[['description', 'keywords', 'logo', 'copyright', 'pagetitle_template', 'backoffice_theme', 'backoffice_theme_sublayout', 'backoffice_theme_pagination', 'backoffice_theme_loginlayout', 'backoffice_indexing', 'maintenance_theme', 'maintenance_theme_sublayout', 'maintenance_indexing', 'theme', 'theme_sublayout', 'theme_pagination', 'theme_loginlayout', 'theme_indexing', 'theme_include_script', 'construction_date', 'construction_text', 'analytic', 'analytic_property'], 'safe'],
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
			'app_type' => Yii::t('app', 'Site Type'),
			'online' => Yii::t('app', 'Maintenance Mode'),
			'name' => Yii::t('app', 'Site Title'),
			'description' => Yii::t('app', 'Site Description'),
			'keywords' => Yii::t('app', 'Site Keyword'),
			'logo' => Yii::t('app', 'Logo'),
			'copyright' => Yii::t('app', 'Copyright'),
			'pagetitle_template' => Yii::t('app', 'Page Title Template'),
			'backoffice_theme' => Yii::t('app', 'Backend Theme'),
			'backoffice_theme_sublayout' => Yii::t('app', 'Backend Sublayout'),
			'backoffice_theme_pagination' => Yii::t('app', 'Backend Pagination'),
			'backoffice_theme_loginlayout' => Yii::t('app', 'Backend Login Layout'),
			'backoffice_indexing' => Yii::t('app', 'Backend Search Engine Indexing'),
			'maintenance_theme' => Yii::t('app', 'Maintenance Theme'),
			'maintenance_theme_sublayout' => Yii::t('app', 'Maintenance Sublayout'),
			'maintenance_indexing' => Yii::t('app', 'Maintenance Search Engine Indexing'),
			'theme' => Yii::t('app', 'Frontend Theme'),
			'theme_sublayout' => Yii::t('app', 'Frontend Sublayout'),
			'theme_pagination' => Yii::t('app', 'Frontend Pagination'),
			'theme_loginlayout' => Yii::t('app', 'Frontend Login Layout'),
			'theme_indexing' => Yii::t('app', 'Frontend Search Engine Indexing'),
			'theme_include_script' => Yii::t('app', 'Head Scripts/Styles'),
			'construction_date' => Yii::t('app', 'Offline Date'),
			'construction_text' => Yii::t('app', 'Maintenance Text'),
			'construction_text[comingsoon]' => Yii::t('app', 'Coming Soon Text'),
			'construction_text[maintenance]' => Yii::t('app', 'Maintenance Text'),
			'analytic' => Yii::t('app', 'Google Analytics'),
			'analytic_property' => Yii::t('app', 'GA Tracking ID'),
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
		$this->logo = Yii::$app->setting->get($this->getId('logo'));
		$this->copyright = unserialize(Yii::$app->setting->get($this->getId('copyright')));
		$this->pagetitle_template = Yii::$app->setting->get($this->getId('pagetitle_template'), '{title} | {small-name} - {long-name}');
		$this->backoffice_theme = Yii::$app->setting->get($this->getId('backoffice_theme'));
		$this->backoffice_theme_sublayout = Yii::$app->setting->get($this->getId('backoffice_theme_sublayout'));
		$this->backoffice_theme_pagination = Yii::$app->setting->get($this->getId('backoffice_theme_pagination'));
		$this->backoffice_theme_loginlayout = Yii::$app->setting->get($this->getId('backoffice_theme_loginlayout'));
		$this->backoffice_indexing = Yii::$app->setting->get($this->getId('backoffice_indexing'), 1);
		$this->maintenance_theme = Yii::$app->setting->get($this->getId('maintenance_theme'));
		$this->maintenance_theme_sublayout = Yii::$app->setting->get($this->getId('maintenance_theme_sublayout'));
		$this->maintenance_indexing = Yii::$app->setting->get($this->getId('maintenance_indexing'), 1);
		$this->theme = Yii::$app->setting->get($this->getId('theme'));
		$this->theme_sublayout = Yii::$app->setting->get($this->getId('theme_sublayout'));
		$this->theme_pagination = Yii::$app->setting->get($this->getId('theme_pagination'));
		$this->theme_loginlayout = Yii::$app->setting->get($this->getId('theme_loginlayout'));
		$this->theme_indexing = Yii::$app->setting->get($this->getId('theme_indexing'), 1);
		$this->theme_include_script = Yii::$app->setting->get($this->getId('theme_include_script'));
		$this->construction_date = Yii::$app->setting->get($this->getId('construction_date'));
		$this->construction_text = unserialize(Yii::$app->setting->get($this->getId('construction_text')));
		$this->analytic = Yii::$app->setting->get($this->getId('analytic'), 1);
		$this->analytic_property = Yii::$app->setting->get($this->getId('analytic_property'));
		$this->old_logo = $this->logo;
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
			'demo-app' => Yii::t('app', 'Demo Application'),
			'demo-theme' => Yii::t('app', 'Demo Theme'),
		);

		if ($value !== null) {
			return $items[$value];
        } else {
			return $items;
        }
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

		if ($value !== null) {
			return $items[$value];
        } else {
			return $items;
        }
	}

	/**
	 * function getAnalytics
	 */
	public static function getAnalytics($value=null)
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
	 * @param returnAlias set true jika ingin kembaliannya path alias atau false jika ingin string
	 * relative path. default true.
	 */
	public static function getUploadPath($returnAlias=true, $app) 
	{
		return ($returnAlias ? join('/', [Yii::getAlias('@public'), $app]) : $app);
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if ($this->app_type == '') {
			$this->addError('app_type', Yii::t('app', '{attribute} cannot be blank.', ['attribute' => $this->getAttributeLabel('app_type')]));
        }

		if ($this->name['small'] == '' || $this->name['long'] == '') {
			$this->addError('name', Yii::t('app', '{attribute} cannot be blank.', ['attribute' => $this->getAttributeLabel('name')]));
        }

		if ($this->online == '') {
			$this->addError('online', Yii::t('app', '{attribute} cannot be blank.', ['attribute' => $this->getAttributeLabel('online')]));
        }

		if ($this->online != 1) {
			if ($this->construction_date == '') {
				$this->addError('construction_date', Yii::t('app', '{attribute} cannot be blank.', ['attribute' => $this->getAttributeLabel('construction_date')]));
            }
			if ($this->online == 0 && $this->construction_text['maintenance'] == '') {
				$this->addError('construction_text', Yii::t('app', '{attribute} cannot be blank.', ['attribute' => $this->getAttributeLabel('construction_text[maintenance]')]));
            }
			if ($this->online == 2 && $this->construction_text['comingsoon'] == '') {
				$this->addError('construction_text', Yii::t('app', '{attribute} cannot be blank.', ['attribute' => $this->getAttributeLabel('construction_text[comingsoon]')]));
            }
		}

		if ($this->analytic == 1) {
			if ($this->analytic_property == '') {
				$this->addError('analytic_property', Yii::t('app', '{attribute} cannot be blank.', ['attribute' => $this->getAttributeLabel('analytic_property')]));
            }
		}

		$this->logo = UploadedFile::getInstance($this, 'logo');
		if ($this->logo instanceof UploadedFile && !$this->logo->getHasError()) {
			$logoFileType = $this->formatFileType('png, bmp');
			if (!in_array(strtolower($this->logo->getExtension()), $logoFileType)) {
				$this->addError('logo', Yii::t('app', 'The file {name} cannot be uploaded. Only files with these extensions are allowed: {extensions}', [
					'name' => $this->logo->name,
					'extensions' => $this->formatFileType($logoFileType, false),
				]));
			}
		}

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

		$this->name = serialize($this->name);
		$this->copyright = serialize($this->copyright);
		if ($this->construction_date != '') {
			$this->construction_date = Yii::$app->formatter->asDate($this->construction_date, 'php:Y-m-d');
        }
		$this->construction_text = serialize($this->construction_text);

		$uploadPath = join('/', [self::getUploadPath(true, $this->app)]);
		$verwijderenPath = join('/', [self::getUploadPath(true, $this->app), 'verwijderen']);
		$this->createUploadDirectory(self::getUploadPath(true, $this->app));

		$this->logo = UploadedFile::getInstance($this, 'logo');
		if ($this->logo instanceof UploadedFile && !$this->logo->getHasError()) {
			$fileName = 'logo_'.time().'.'.strtolower($this->logo->getExtension());
			if ($this->logo->saveAs(join('/', [$uploadPath, $fileName]))) {
				if ($this->old_logo != '' && file_exists(join('/', [$uploadPath, $this->old_logo]))) {
					rename(join('/', [$uploadPath, $this->old_logo]), join('/', [$verwijderenPath, time().'_'.$this->old_logo]));
                }
				$this->logo = $fileName;
			}
		} else {
			if ($this->logo == '') {
				$this->logo = $this->old_logo;
            }
		}
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

		Yii::$app->setting->set($this->getId('app_type'), $this->app_type);
		Yii::$app->setting->set($this->getId('online'), $this->online);
		Yii::$app->setting->set($this->getId('name'), $this->name);
		Yii::$app->setting->set($this->getId('description'), $this->description);
		Yii::$app->setting->set($this->getId('keywords'), $this->keywords);
		Yii::$app->setting->set($this->getId('logo'), $this->logo);
		Yii::$app->setting->set($this->getId('copyright'), $this->copyright);
		Yii::$app->setting->set($this->getId('pagetitle_template'), $this->pagetitle_template);
		Yii::$app->setting->set($this->getId('backoffice_theme'), $this->backoffice_theme);
		Yii::$app->setting->set($this->getId('backoffice_theme_sublayout'), $this->backoffice_theme_sublayout);
		Yii::$app->setting->set($this->getId('backoffice_theme_pagination'), $this->backoffice_theme_pagination);
		Yii::$app->setting->set($this->getId('backoffice_theme_loginlayout'), $this->backoffice_theme_loginlayout);
		Yii::$app->setting->set($this->getId('backoffice_indexing'), $this->backoffice_indexing);
		Yii::$app->setting->set($this->getId('maintenance_theme'), $this->maintenance_theme);
		Yii::$app->setting->set($this->getId('maintenance_theme_sublayout'), $this->maintenance_theme_sublayout);
		Yii::$app->setting->set($this->getId('maintenance_indexing'), $this->maintenance_indexing);
		Yii::$app->setting->set($this->getId('theme'), $this->theme);
		Yii::$app->setting->set($this->getId('theme_sublayout'), $this->theme_sublayout);
		Yii::$app->setting->set($this->getId('theme_pagination'), $this->theme_pagination);
		Yii::$app->setting->set($this->getId('theme_loginlayout'), $this->theme_loginlayout);
		Yii::$app->setting->set($this->getId('theme_indexing'), $this->theme_indexing);
		Yii::$app->setting->set($this->getId('theme_include_script'), $this->theme_include_script);
		Yii::$app->setting->set($this->getId('construction_date'), $this->construction_date);
		Yii::$app->setting->set($this->getId('construction_text'), $this->construction_text);
		Yii::$app->setting->set($this->getId('analytic'), $this->analytic);
		Yii::$app->setting->set($this->getId('analytic_property'), $this->analytic_property);
		
		return true;
	}
}