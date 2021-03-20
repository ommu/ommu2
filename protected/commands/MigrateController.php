<?php
namespace app\commands;

use Yii;
use \yii\console\Controller;
use \yii\helpers\Console;

class MigrateController extends \yii\console\controllers\MigrateController
{
	public $migrationPath = '@app/migrations';
	public $includeModuleMigrations = false;
	public $migrationTable = 'ommu_migration';
	protected $migrationPathMap = [];

	/**
	 * {@inheritdoc}
	 */
	public function options($actionID)
	{
		if ($actionID == 'up') {
			return array_merge(parent::options($actionID), ['includeModuleMigrations']);
		}
		return parent::options($actionID);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getNewMigrations()
	{
		if (!$this->includeModuleMigrations) {
			return parent::getNewMigrations();
		}

		$this->migrationPathMap = [];
		$migrations = [];
		foreach($this->getMigrationPaths() as $migrationPath) {
			$this->migrationPath = $migrationPath;
			$migrations = array_merge($migrations, parent::getNewMigrations());
			$this->migrationPathMap[$migrationPath] = $migrations;
		}

		sort($migrations);
		return $migrations;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function createMigration($class)
	{
		if ($this->includeModuleMigrations) {
			$this->migrationPath = $this->getMigrationPath($class);
		}
		return parent::createMigration($class);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getMigrationPath($migration)
	{
		foreach($this->migrationPathMap as $path => $migrations) {
			if (in_array($migration, $migrations)) {
				return $path;
			}
		}
		throw new \yii\console\Exception('Could not find path for: ' . $migration);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getMigrationPaths()
	{
		$migrationPaths = ['base' => $this->migrationPath];
		foreach(\Yii::$app->getModules() as $id => $config) {
			if (is_array($config) && isset($config['class'])) {
				$reflector = new \ReflectionClass($config['class']);
				$path = dirname($reflector->getFileName()) . '/migrations';
				if (is_dir($path)) {
					$migrationPaths[$id] = $path;
				}
			}
		}
		return $migrationPaths;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function getMigrationTable()
	{
		$controller = new self('migrate', Yii::$app);
		return $controller->migrationTable;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function webMigrateShow()
	{
		ob_start();
		$controller                          = new self('migrate', Yii::$app);
		$controller->db                      = Yii::$app->db;
		$controller->interactive             = false;
		$controller->includeModuleMigrations = true;
		$controller->color                   = false;
		$controller->runAction('new');
		return ob_get_clean();
	}

	/**
	 * {@inheritdoc}
	 */
	public static function webMigrateAll()
	{
		ob_start();
		$controller                          = new self('migrate', Yii::$app);
		$controller->db                      = Yii::$app->db;
		$controller->interactive             = false;
		$controller->includeModuleMigrations = true;
		$controller->color                   = false;
		$controller->runAction('up');
		return ob_get_clean();
	}

	/**
	 * {@inheritdoc}
	 */
	public static function webMigrateUp($migrationPath)
	{
		ob_start();
		$controller                = new self('migrate', Yii::$app);
		$controller->db            = Yii::$app->db;
		$controller->interactive   = false;
		$controller->migrationPath = $migrationPath;
		$controller->color         = false;
		$controller->runAction('up');
		return ob_get_clean();
	}

	/**
	 * {@inheritdoc}
	 */
	public function stdout($string)
	{
		if (Yii::$app instanceof \yii\web\Application) {
			print $string;
		} else {
			return parent::stdout($string);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function stderr($string)
	{
		if (Yii::$app instanceof \yii\web\Application) {
			print $string;
		} else {
			return parent::stderr($string);
		}
	}
}
