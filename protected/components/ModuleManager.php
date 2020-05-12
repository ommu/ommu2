<?php
/**
 * ModuleManager
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 24 December 2017, 17:22 WIB
 * @link https://github.com/ommu/ommu
 *
 */

namespace app\components;

use Yii;
use \yii\base\Exception;
use \yii\helpers\FileHelper;
use ommu\core\models\Modules;

class ModuleManager extends \yii\base\Component
{
	/**
	 * @var boolean tempat menyimpan status apakah backup akan dilakukan setelah penghapusan modul
	 */
	public $createBackup = true;
	/**
	 * @var array tempat menyimpan nama dan class module yang terdapat pada folder module.
	 */
	protected $modules;
	/**
	 * @var array tempat menyimpan module yang dalam kondisi aktif/enable.
	 */
	protected $moduleEnabled = [];
	/**
	 * @var array tempat menyimpan daftar core module
	 */
	protected $moduleCores = [];

	/**
	 * {@inheritdoc}
	 */
	public function init() 
	{
		parent::init();

		if(Yii::$app instanceof console\Application)
			$this->moduleEnabled = [];
		else
			$this->moduleEnabled = Modules::getEnableIds();
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
			throw new \yii\base\InvalidConfigException('Module configuration requires an id and class attribute!');

		$isCore      = (isset($config['core']) && $config['core']);
		$isBootstrap = (isset($config['bootstrap']) && $config['bootstrap']);
		$this->modules[$config['id']] = $config['class'];

		// menambahkan alias berdasarkan namaspace
		if(isset($config['namespace']))
			Yii::setAlias('@' . str_replace('\\', '/', $config['namespace']), $basePath);

		// menambahkan alias berdasarkan nama module
		Yii::setAlias('@' . $config['id'], $basePath);

		if(!$isCore && !in_array($config['id'], $this->moduleEnabled))
			return;

		// menambahkan konfigurasi modules kosong jika pada file konfigurasi tidak ditemukan
		if(!isset($config['modules']))
			$config['modules'] = [];

		// mengelompokkan core module
		if($isCore)
			$this->moduleCores[$config['id']] = $config['class'];

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
				\yii\base\Event::on($event['class'], $event['event'], $event['callback']);
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
			if(!isset($options['includeCoreModules']) || $options['includeCoreModules'] === false) {
				if(in_array($class, $this->moduleCores))
					continue;
			}
			
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
	 * @return void|object informasi module
	 */
	public function getModule($id)
	{
		if(Yii::$app->hasModule($id))
			return Yii::$app->getModule($id, true);

		if(isset($this->modules[$id])) {
			$class = $this->modules[$id];
			return Yii::createObject($class, [$id, Yii::$app]);
		}
		throw new Exception(Yii::t('app', 'Could not find/load requested module: {module-id}', array('module-id'=>$id)));
	}

	/**
	 * Menghapus cache module.
	 *
	 * @return void
	 */
	public function flushCache()
	{
		Yii::$app->cache->delete(\app\components\bootstrap\ModuleAutoLoader::CACHE_ID);
		Yii::$app->cache->delete(Modules::CACHE_ENABLE_MODULE_IDS);
	}

	/**
	 * Memeriksa apakah modul dapat dihapus atau tidak.
	 *
	 * @param string $moduleId
	 * @return boolean true|false
	 */
	public function canRemoveModule($moduleId)
	{
		$module = $this->getModule($moduleId);

		if($module === null)
			return false;

		$modulePath = $module->getBasePath();
		$configFile = join('/', [$modulePath, 'config.php']);
		if(is_file($configFile))
			$config = require($configFile);

		$isCore = (isset($config['core']) && $config['core']);

		if(!$isCore && strpos($modulePath, Yii::getAlias(Yii::$app->params['moduleMarketplacePath'])) !== false)
			return true;

		return false;
	}

	/**
	 * Menghapus modul dari folder. modul yang dihapus akan dibackup
	 * di folder @app/runtime/module_backups
	 *
	 * @param \app\components\Module $module
	 * @return void
	 */
	public function remove(\app\components\Module $module)
	{
		if($this->createBackup) {
			$moduleBackupPath = Yii::getAlias('@runtime/module_backups');
			if(!is_dir($moduleBackupPath)) {
				if(@mkdir($moduleBackupPath, 0777))
					throw new Exception('Could not create module backup folder!.');
			}

			$moduleBackupNewPath = join('/', [$moduleBackupPath, $module->id.'_'.time()]);
			$moduleBasePath = $module->getBasePath();
			FileHelper::copyDirectory($moduleBasePath, $moduleBackupNewPath);
			FileHelper::removeDirectory($moduleBasePath);
		}
		
		$this->flushCache();
	}

	/**
	 * Memperbarui status modul ke database dan register module
	 *
	 * @param \app\components\Module $module
	 * @return void
	 */
	public function enable(\app\components\Module $module)
	{
		$model = Modules::findOne(['module_id' => $module->id]);
		$model->enabled = 1;

		if($model->save()) {
			$this->moduleEnabled[] = $module->id;
			$this->register($module->getBasePath());

			return $model;
		} else {
			return Yii::t('app', '{module-id} module can\'t be enabled. Errors: {errors}', array(
				'module-id'=>ucfirst($module->id),
				'errors'=>print_r($model->errors, true),
			));
		}
	}

	/**
	 * disable modul berdasarkan klas modul
	 *
	 * @param \app\components\Module $module, modul harus turunan kelas ini.
	 * @return void
	 */
	public function disable(\app\components\Module $module)
	{
		$model = Modules::findOne(['module_id' => $module->id]);
		$model->enabled = 0;

		if($model != null) {
			if($model->save()) {
				if(($key=array_search($module->id, $this->moduleEnabled)) !== false)
					unset($this->moduleEnabled[$key]);
				Yii::$app->setModule($module->id, 'null');

				return $model;
			} else {
				return Yii::t('app', '{module-id} module can\'t be disabled. Errors: {errors}', array(
					'module-id'=>ucfirst($module->id),
					'errors'=>print_r($model->errors, true),
				));
			}
		} else {
			return Yii::t('app', '{module-id} module not found in database.', array(
				'module-id'=>ucfirst($module->id),
			));
		}
	}

	/**
	 * uninstall modul berdasarkan klas modul.
	 *
	 * @param \app\components\Module $module, modul harus turunan kelas ini.
	 * @return void
	 */
	public function uninstall(\app\components\Module $module)
	{
		if(($key=array_search($module->id, $this->moduleEnabled)) !== false)
			unset($this->moduleEnabled[$key]);
		Yii::$app->setModule($module->id, 'null');

		$this->remove($module);
	}

	/**
	 * Mengembalikan daftar module yang terdapat pada database.
	 *
	 * @return array daftar module
	 */
	public function getModulesFromDb()
	{
		$model = Modules::find()->all();
		return \yii\helpers\ArrayHelper::map($model, 'module_id', 'module_id');
	}
	
	/**
	 * Install modul ke database.
	 */
	public function setModules()
	{
		$moduleFromDB = $this->getModulesFromDb();
		foreach($this->modules as $key => $val) {
			if(array_key_exists($key, $this->moduleCores))
				continue;

			if(!in_array($key, $moduleFromDB)) {
				$module = new Modules();
				$module->module_id = $key;
				$module->save();
			}
		}

		foreach($moduleFromDB as $val) {
			if(array_key_exists($val, $this->modules))
				continue;
			
			Modules::findOne(['module_id' => $val])->delete();
		}
	}
}
