<?php
/**
 * Application class
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 7 December 2017, 05:36 WIB
 * @link https://github.com/ommu/ommu
 */

namespace app\components;

use Yii;
use mdm\admin\components\Helper;

class Application extends \yii\web\Application
{
	const API_VERSION = '1.0.0';

	/**
	 * Mengembalikan id aplikasi
	 * misalnya http://localhost/ommu maka akan mengembalikan ommu
	 *
	 * @return string
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
	 * Memeriksa apakah aplikasi berjalan pada mode social media atau tidak (baca:company profile)
	 *
	 * @return boolean true|false
	 */
	public function isSocialMedia(): bool
	{
		return isset(Yii::$app->params['communityApps']) ? Yii::$app->params['communityApps'] : false;
	}

	/**
	 * Memeriksa apakah aplikasi berjalan pada mode maintenance
	 *
	 * @return boolean true|false
	 */
	public function isMaintenance(): bool
	{
		$setting = \app\models\CoreSettings::find()
			->select(['id', 'online'])
			->where(['id' => 1])
			->one();

		return (!$setting->view->online && (Yii::$app->user->isGuest || (!Yii::$app->user->isGuest && !in_array(Yii::$app->user->identity->level_id, [1,2]))));
	}

	/**
	 * Memeriksa apakah aplikasi berjalan pada mode demo applikasi
	 *
	 * @return boolean true|false
	 */
	public function isDemoApps(): bool
	{
		return isset(Yii::$app->params['demoApps']) ? Yii::$app->params['demoApps'] : false;
	}

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
	 * Mengembalikan semua koneksi database yg dipakai pada aplikasi ini dalam bentuk array
	 * nama komponen db. misalnya ['db', 'db_ommu', 'db_api']
	 *
	 * $conn = Yii::$app->getAllConnection();
	 * $dsn = Yii::$app->$conn[0]->dsn;
	 * 
	 * @return array
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
	 * Mengembalikan versi major API
	 *
	 * @return int
	 */
	public function getMajorApiVersion()
	{
		$version = explode('.', self::API_VERSION);
		return $version[0];
	}

	/**
	 * Mengembalikan versi minor API
	 *
	 * @return int
	 */
	public function getMinorApiVersion()
	{
		$version = explode('.', self::API_VERSION);
		return $version[1];
	}

	/**
	 * Mengembalikan versi release API
	 *
	 * @return int
	 */
	public function getReleaseApiVersion()
	{
		$version = explode('.', self::API_VERSION);
		return $version[2];
	}
}
