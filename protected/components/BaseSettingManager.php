<?php
/**
 * BaseSettingManager class
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 December 2017, 15:46 WIB
 * @link https://github.com/ommu/ommu
 */

namespace app\components;

use Yii;

abstract class BaseSettingManager extends \yii\base\Component
{
	/**
	 * @var string tempat menyimpan nama module untuk pengelompokkan pengaturan.
	 */
	public $moduleId = null;
	/**
	 * @var string|array tempat menyimpan data pengaturan (baca:setting).
	 */
	protected $_loaded = null;
	/**
	 * @var string|array tempat menyimpan nama model pengaturan yang digunakan untuk menyimpan data pada database.
	 */
	public $modelClass = 'ommu\core\models\Settings';

	/**
	 * init()
	 * Menyimpan data setting pada public varibale.
	 */
	public function init()
	{
		if($this->moduleId === null)
			throw new \Exception('Could not determine module id');

		$this->loadValues();
		parent::init();
	}

	/**
	 * Mengembalikan object !(isNewRecord) setting setelah ditambahkan where nama module.
	 */
	protected function find()
	{
		$modelClass = $this->modelClass;

		return $modelClass::find()->andWhere(['module_id' => $this->moduleId]);
	}

	/**
	 * Menambahkan atau memperbarui setting berdasarkan attribute.
	 *     jika terjadi perubahan pada attribute setting, cache setting akan dihapus.
	 * 
	 * @param string $name attribute
	 * @param string $value nilai untuk mengisi attribute setting
	 */
	public function set($name, $value)
	{
		if($value === null)
			return $this->delete($name);

		$value = (string)$value;
		$record = $this->find()->andWhere(['name' => $name])->one();
		if($record !== null && $record->value == $value)
			return false;

		if($record === null) {
			$record = $this->createRecord();
			$record->name = $name;
		}
		$record->value = $value;

		if(!$record->save())
			throw new \yii\base\Exception('Could not store setting! (' .print_r($record->getErrors(), true) . ')');

		$this->_loaded[$name] = $value;
		$this->invalidateCache();
	}

	/**
	 * Mengembalikan nilai attribute setting pada cache.
	 *     jika pada cache attribute setting tidak ditemukan maka akan dikembalikan dengan nilai default.
	 * 
	 * @param string $name attribute
	 * @param string $value nilai default
	 */
	public function get($name, $default=null)
	{
		return isset($this->_loaded[$name]) ? $this->_loaded[$name] : $default;
	}

	/**
	 * Mengembalikan nilai attribute setting pada database.
	 *     jika pada database attribute setting tidak ditemukan maka akan dikembalikan dengan nilai default.
	 * 
	 * @param string $name attribute
	 * @param string $value nilai default
	 */
	public function getUncached($name, $default)
	{
		$record = $this->find()->andWhere(['name' => $name])->one();
		return ($record !== null && $record->value != '') ? $record->value : $default;
	}

	/**
	 * Menghapus attribute setting pada database dan menghapus cache setting.
	 * 
	 * @param string $name attribute
	 */
	public function delete($name)
	{
		$record = $this->find()->andWhere(['name' => $name])->one();
		if($record !== null)
			$record->delete();

		if(isset($this->_loaded[$name]))
			unset($this->_loaded[$name]);

		$this->invalidateCache();
	}

	/**
	 * Menyimpan data setting pada public varibale.
	 * 
	 * @see init();
	 */
	protected function loadValues()
	{
		$cached = Yii::$app->cache->get($this->getCacheKey());
		if($cached === false) {
			$this->_loaded = [];
			$settings = &$this->_loaded;

			array_map(function($record) use (&$settings) {
				$settings[$record->name] = $record->value;
			}, $this->find()->all());
			Yii::$app->cache->set($this->getCacheKey(), $this->_loaded);

		} else
			$this->_loaded = $cached;
	}

	/**
	 * Menghapus cache setting.
	 */
	protected function invalidateCache()
	{
		Yii::$app->cache->delete($this->getCacheKey());
	}

	/**
	 * Mengembalikan nama cache setting.
	 */
	protected function getCacheKey()
	{
		return 'settings-' . $this->moduleId;
	}

	/**
	 * Mengembalikan object isNewRecord setting.
	 */
	protected function createRecord()
	{
		$model = new $this->modelClass;
		$model->module_id = $this->moduleId;

		return $model;
	}
}
