<?php
/**
 * BaseSettingManager class
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 20 December 2017, 15:46 WIB
 * @link https://github.com/ommu/ommu
 */

namespace app\components;

use Yii;
use \yii\base\Component;

abstract class BaseSettingManager extends Component
{
	/**
	 * {@inheritdoc}
	 */
	public $moduleId = null;
	/**
	 * {@inheritdoc}
	 */
	protected $_loaded = null;
	/**
	 * {@inheritdoc}
	 */
	public $modelClass = 'app\models\Settings';

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		if($this->moduleId === null)
			throw new \Exception('Could not determine module id');

		$this->loadValues();
		parent::init();
	}

	/**
	 * {@inheritdoc}
	 */
	protected function find()
	{
		$modelClass = $this->modelClass;

		return $modelClass::find()->andWhere(['module_id' => $this->moduleId]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function set($name, $value)
	{
		if($value === null)
			return $this->delete($name);

		$record = $this->find()->andWhere(['name' => $name])->one();
		if($record === null) {
			$record = $this->createRecord();
			$record->name = $name;
		}
		$record->value = (string)$value;

		if(!$record->save())
			throw new \yii\base\Exception('Could not store setting! (' .print_r($record->getErrors(), true) . ')');

		$this->_loaded[$name] = $value;
		$this->invalidateCache();
	}

	/**
	 * {@inheritdoc}
	 * 
	 */
	public function get($name, $default=null)
	{
		return isset($this->_loaded[$name]) ? $this->_loaded[$name] : $default;
	}

	/**
	 * {@inheritdoc}
	 * 
	 */
	public function getUncached($name, $default)
	{
		$record = $this->find()->andWhere(['name' => $name])->one();
		return ($record !== null && $record->value != '') ? $record->value : $default;
	}

	/**
	 * {@inheritdoc}
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
	 * {@inheritdoc}
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
	 * {@inheritdoc}
	 */
	protected function invalidateCache()
	{
		Yii::$app->cache->delete($this->getCacheKey());
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getCacheKey()
	{
		return 'settings-' . $this->moduleId;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function createRecord()
	{
		$model = new $this->modelClass;
		$model->module_id = $this->moduleId;

		return $model;
	}
}
