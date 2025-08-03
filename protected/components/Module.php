<?php
/**
 * Module
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 24 December 2017, 17:22 WIB
 * @link https://github.com/ommu/ommu2
 *
 */

namespace app\components;

use Yii;

class Module extends \yii\base\Module
{
	/**
	 * @var array tempat menyimpan informasi modul yang diambil dari file module.json
	 */
	private $_moduleInfo = null;

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		parent::init();

		if (is_array($config = $this->getModuleConfig())) {
			$this->params = \yii\helpers\ArrayHelper::merge(
				$this->params,
				$config
			);
		}
		
		$this->set('setting', [
			'class' => \app\components\SettingManager::className(),
			'moduleId' => $this->id,
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getLayoutPath()
	{
		if (Yii::$app->view->theme) {
			return Yii::$app->view->theme->basePath . DIRECTORY_SEPARATOR . 'layouts';
        } else {
			return parent::getLayoutPath();
        }
	}

	/**
	 * Mengembalikan nama modul
	 *
	 * @return string nama modul
	 */
	public function getName()
	{
		$info = $this->getModuleInfo();
		if ($info['name']) {
			return $info['name'];
        }

		return $this->id;
	}

	/**
	 * Mengembalikan deskripsi modul
	 *
	 * @return string description
	 */
	public function getDescription()
	{
		$info = $this->getModuleInfo();
		if ($info['description']) {
			return $info['description'];
        }

		return '';
	}

	/**
	 * Mengembalikan versi modul
	 *
	 * @return string versi
	 */
	public function getVersion()
	{
		$info = $this->getModuleInfo();
		if ($info['version']) {
			return $info['version'];
        }

		return '';
	}

	/**
	 * Mengembalikan letak folder migrations pada current modul.
	 *
	 * @return string letak folder migrations
	 */
	private function getMigrationPath()
	{
		return join('/', [$this->getBasePath(), 'migrations']);
	}

	/**
	 * enable modul dan jalankan semua migrasi pada current modul.
	 *
	 * @see migrate()
	 * @return boolean true|false
	 */
	public function enable()
	{
		$module = Yii::$app->moduleManager->enable($this);

		if (is_object($module) && $module->installed == 0) {
			$module->installed = 1;
			$this->migrate();
			$module->save();
		}

		return $module;
	}

	/**
	 * disable modul.
	 */
	public function disable()
	{
		$module = Yii::$app->moduleManager->disable($this);

		return $module;
	}

	/**
	 * Uninstall modul
	 * Opsi ini akan menjalankan file migrasi dengan nama uninstall.php pada folder migrations
	 * file uninstall.php tidak harus berisi perintah untuk menghapus data pd database atau menghapus
	 * tabel, tetapi bisa juga menjalankan perintah php lainnya misalnya menghapus file image pada
	 * folder tertentu.
	 */
	public function uninstall()
	{
		$migrationPath = $this->getMigrationPath();
		$uninstallMigration = join('/', [$migrationPath, 'uninstall.php']);
		if (file_exists($uninstallMigration)) {
			ob_start();
			require_once($uninstallMigration);
			$migration = new \uninstall();
			try {
				$migration->up();
			} catch(\yii\db\Exception $ex) {
				;
			}
			ob_get_clean();

			$migrations = opendir($migrationPath);
			while(false !== ($migration = readdir($migrations))) {
				if ($migration == '.' || 
                    $migration == '..' || 
                    $migration == 'uninstall.php') {
                        continue;
                }

				Yii::$app->db->createCommand()->delete(\app\commands\MigrateController::getMigrationTable(), [
					'version' => str_replace('.php', '', $migration)])->execute();
			}
		}

		Yii::$app->moduleManager->uninstall($this);
	}

	/**
	 * Menjalankan semua file migrasi pada folder migrations module yg bersangkutan.
	 * 
	 * @return void
	 */
	public function migrate()
	{
		$migrationPath = $this->getMigrationPath();
		if (is_dir($migrationPath)) {
			\app\commands\MigrateController::webMigrateUp($migrationPath);
        }
	}

	/**
	 * Mengembalikan informasi module dari file module.json
	 *
	 * @return array info modul
	 */
	protected function getModuleInfo()
	{
		if ($this->_moduleInfo != null) {
			return $this->_moduleInfo;
        }

		$moduleJson = file_get_contents(join('/', [$this->getBasePath(), 'module.json']));
		return \yii\helpers\Json::decode($moduleJson);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getModuleConfig()
	{
		$configFile = $this->getBasePath() . DIRECTORY_SEPARATOR . $this->id .'.yaml';
		if (!file_exists($configFile)) {
			return false;
        }
		
		return \Symfony\Component\Yaml\Yaml::parseFile($configFile);
	}
}
