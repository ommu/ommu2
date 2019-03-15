<?php

namespace app\components\i18n;

use Yii;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 * Configs
 * Used to configure some values. To set config you can use [[\yii\base\Application::$params]]
 *
 * ```
 * return [
 *     'i18n.config' => [
 *         'db' => 'customDb',
 *         'sourceTable' => '{{%source_message}}',
 *         'translateTable' => '{{%message}}',
 *     ]
 * ];
 * ```
 *
 * or use [[\Yii::$container]]
 *
 * ```
 * Yii::$container->set('app\components\i18n\Configs',[
 *     'db' => 'customDb',
 *     'sourceTable' => 'source_message',
 *     'translateTable' => 'message',
 * ]);
 * ```
 *
 */

class Configs extends \yii\base\BaseObject
{
	/**
	 * @var Connection Database connection.
	 */
	public $db = 'db';

	/**
	 * @var string Menu table name.
	 */
	public $sourceTable = '{{%source_message}}';

	/**
	 * @var string Menu table name.
	 */
	public $translateTable = '{{%message}}';

	/**
	 * @var array
	 */
	public $options;

	/**
	 * @var self Instance of self
	 */
	private static $_instance;
	private static $_classes = [
		'db' => 'yii\db\Connection',
	];

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		foreach (self::$_classes as $key => $class) {
			try {
				$this->{$key} = empty($this->{$key}) ? null : Instance::ensure($this->{$key}, $class);
			} catch (\Exception $exc) {
				$this->{$key} = null;
				Yii::error($exc->getMessage());
			}
		}
	}

	/**
	 * Create instance of self
	 * @return static
	 */
	public static function instance()
	{
		if (self::$_instance === null) {
			$type = ArrayHelper::getValue(Yii::$app->params, 'i18n.config', []);
			if (is_array($type) && !isset($type['class']))
				$type['class'] = static::className();

			return self::$_instance = Yii::createObject($type);
		}

		return self::$_instance;
	}

	public static function __callStatic($name, $arguments)
	{
		$instance = static::instance();
		if ($instance->hasProperty($name))
			return $instance->$name;
		else {
			if (count($arguments))
				$instance->options[$name] = reset($arguments);
			else
				return array_key_exists($name, $instance->options) ? $instance->options[$name] : null;
		}
	}

	/**
	 * @return Connection
	 */
	public static function db()
	{
		return static::instance()->db;
	}

	/**
	 * @return string
	 */
	public static function sourceTable()
	{
		return static::instance()->sourceTable;
	}

	/**
	 * @return string
	 */
	public static function translateTable()
	{
		return static::instance()->translateTable;
	}
}
