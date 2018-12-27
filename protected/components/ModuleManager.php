<?php
/**
 * ModuleManager
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 24 December 2017, 17:22 WIB
 * @link http://github.com/ommu/ommu
 *
 */

namespace app\components;

use Yii;
use \yii\base\Exception;
use \yii\base\Event;
use \yii\base\InvalidConfigException;
use \yii\helpers\FileHelper;

use app\components\bootstrap\ModuleAutoLoader;
use app\models\Modules;

class ModuleManager extends \yii\base\Component
{
	/**
	 * @var array tempat menyimpan nama dan class module yang terdapat pada folder module.
	 */
	protected $modules;
	/**
	 * @var array tempat menyimpan module yang dalam kondisi aktif/enable.
	 */
	protected $enabledModules = [];
	/**
	 * @var array tempat menyimpan daftar core module
	 */
	protected $coreModules = [];

	/**
	 * {@inheritdoc}
	 */
	public function init() 
	{
		parent::init();

		if(Yii::$app instanceof console\Application)
			$this->enabledModules = [];
		else
			$this->enabledModules = Modules::getEnableIds();
	}

	/**
	 * Mendaftarkan module secara masal (menyeluruh).
	 *
	 * @param Array $configs
	 * @return void
	 * @see register()
	 */
	public function registerBulk(Array $configs) 
	{
		foreach($configs as $basePath => $config) {
			$this->register($basePath, $config);
		}
	}

	/**
	 * Mendaftarkan satu modul berdasarkan basePath dan konfigurasi
	 *
	 * @param string basePath dari modul.
	 * @param array modul config
	 * @return void
	 */
	public function register($basePath, $config=null) 
	{
		if($config === null && is_file($basePath . '/config.php'))
			$config = require($basePath . '/config.php');

		if(!isset($config['id']) || !isset($config['class']))
			throw new InvalidConfigException('Module configuration requires an id and class attribute!');

		$isCore      = (isset($config['core']) && $config['core']);
		$isBootstrap = (isset($config['bootstrap']) && $config['bootstrap']);
		$this->modules[$config['id']] = $config['class'];

		// menambahkan alias berdasarkan namaspace
		if(isset($config['namespace']))
			Yii::setAlias('@' . str_replace('\\', '/', $config['namespace']), $basePath);

		// menambahkan alias berdasarkan nama module
		Yii::setAlias('@' . $config['id'], $basePath);

		if(!$isCore && !in_array($config['id'], $this->enabledModules))
			return;

		// menambahkan konfigurasi modules kosong jika pada file konfigurasi tidak ditemukan
		if(!isset($config['modules']))
			$config['modules'] = [];

		// mengelompokkan core module
		if($isCore)
			$this->coreModules[$config['id']] = $config['class'];

		// mendaftarkan urlManager module pada aplikasi
		if(isset($config['urlManagerRules'])) {
			$rules = $config['urlManagerRules'];
			if(is_array($rules) && array_key_exists('class', $rules))
				throw new \Exception('Error rules format not valid!.');
			Yii::$app->urlManager->addRules($rules, false);
		}

		// module konfigurasi
		$moduleConfig = [
			'class'   => $config['class'],
			'modules' => $config['modules']
		];

		$ignoredProperty = ['id', 'class', 'modules', 'core', 'bootstrap', 'tablePrefix', 'events', 'urlManagerRules'];
		foreach($config as $name => $val) {
			if(!in_array($name, $ignoredProperty))
				$moduleConfig[$name] = $val;
		}

		// menambahkan module konfigurasi pada module
		if(isset(Yii::$app->modules[$config['id']]) && is_array(Yii::$app->modules[$config['id']]))
			$moduleConfig = \yii\helpers\ArrayHelper::merge($moduleConfig, Yii::$app->modules[$config['id']]);

		// Bootstraping module
		if($isBootstrap) {
			$_bootstrap   = Yii::$app->bootstrap;
			$_bootstrap[] = (string)$config['class'];
			Yii::$app->bootstrap = $_bootstrap;
		}

		// mendaftarkan module pada yii
		Yii::$app->setModule($config['id'], $moduleConfig);

		// mendaftarkan penanganan events
		if(isset($config['events'])) {
			foreach($config['events'] as $event) {
				Event::on($event['class'], $event['event'], $event['callback']);
			}
		}
	}

	/**
	 * Mengembalikan nama dan class module yang terdapat pada folder module
	 * 
	 * @return array daftar module
	 */
	public function getModules(array $options=[]): array 
	{
		$modules = [];
		foreach($this->modules as $id => $class) {
			if(isset($options['returnClass']) && $options['returnClass'])
				$modules[$id] = $class;
			else {
				$module = $this->getModule($id);
				if($module instanceof \app\components\Module)
					$modules[$id] = $module;
			}
		}
		return $modules;
	}

	/**
	 * Memeriksa apakah module terdapat pada folder module
	 * 
	 * @param string $id nama module
	 * @return boolean true|false
	 */
	public function hasModule($id)
	{
		return (array_key_exists($id, $this->modules));
	}

	/**
	 * Mengembalikan informasi module dalam bentuk object
	 * 
	 * @param string $id nama module
	 * @return void
	 */
	public function getModule($id)
	{
		if(Yii::$app->hasModule($id))
			return Yii::$app->getModule($id, true);

		if(isset($this->modules[$id])) {
			$class = $this->modules[$id];
			return Yii::createObject($class, [$id, Yii::$app]);
		}
		throw new Exception('Could not find/load requested module: ' . $id);
	}
}
