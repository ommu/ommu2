<?php
/**
 * Application class
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (opensource.ommu.co)
 * @created date 7 December 2018, 05:36 WIB
 * @link https://github.com/ommu/ommu
 */

namespace app\components;

use Yii;
use mdm\admin\components\Helper;

class Application extends \yii\web\Application
{
	const API_VERSION = '1.0.0';

	/**
	 * Memeriksa hak akses user berdasarkan route dan user idnya
	 *
	 * @param string $route rute/url dari sebuah request. contoh: /site/index
	 * @param int $userId
	 * @return boolean true|false
	 */
	public function isUserHasAccessToRoute($route, $userId)
	{
		$routes = Helper::getRoutesByUser($userId);
		foreach($routes as $r => $val) {
			if($route == $r)
				return true;
		}
		return false;
	}

	/**
	 * Mendapatkan id aplikasi
	 * misalnya http://localhost/ecc4 maka akan mengembalikan ecc4
	 *
	 * @return string id
	 */
	public static function getAppId() 
	{
		$bn = basename(Yii::getAlias('@webroot'));
		$bn = str_ireplace('-', '_', $bn);
		return $bn;
	}

	/**
	 * Memeriksa apakah aplikasi berjalan pada mode pengembangan atau produksi
	 *
	 * @return boolean true|false
	 */
	public static function isDev(): bool 
	{
		return defined('YII_DEBUG') && ($_SERVER["SERVER_ADDR"] == '127.0.0.1' || $_SERVER["HTTP_HOST"] == 'localhost');
	}

	/**
	 * Mengembalikan semua koneksi database yg dipakai pada aplikasi ini dalam bentuk array
	 * nama komponen db. misalnya ['db', 'db_ecc', 'db_api']
	 *
	 * $conn = Yii::$app->getAllConnection();
	 * $dsn = Yii::$app->$conn[0]->dsn;
	 */
	public function getAllConnection()
	{
		$conn = [];
		foreach($this->components as $key => $val) {
			foreach($val as $k => $items) {
				if($k == 'class' && $items == 'yii\db\Connection') {
					$conn[] = $key;
					continue;
				}
			}
		}
		return $conn;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getMajorApiVersion()
	{
		$version = explode('.', self::API_VERSION);
		return $version[0];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getMinorApiVersion()
	{
		$version = explode('.', self::API_VERSION);
		return $version[1];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getReleaseApiVersion()
	{
		$version = explode('.', self::API_VERSION);
		return $version[2];
	}
}
