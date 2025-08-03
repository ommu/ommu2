<?php
/**
 * Application class
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 7 December 2017, 05:36 WIB
 * @link https://github.com/ommu/ommu2
 */

namespace app\components;

use Yii;
use ommu\core\models\CoreSettings;
use mdm\admin\components\Helper;
use app\components\helpers\TimeHelper;

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
        if (in_array(php_sapi_name(), ['cli', 'cli-server'])) {
            return true;
        }

		return $_SERVER["SERVER_ADDR"] == '127.0.0.1' || $_SERVER["HTTP_HOST"] == 'localhost';
	}

	/**
	 * Memeriksa apakah aplikasi berjalan pada mode social media atau tidak (baca:company profile)
	 *
	 * @return boolean true|false
	 */
	public function isSocialMedia(): bool
	{
		$appType = Yii::$app->setting->get(join('_', [Yii::$app->id, 'app_type']), 'company');

		return $appType == 'community' ? true : false;
	}

	/**
	 * Memeriksa apakah aplikasi berjalan pada mode demo applikasi
	 *
	 * @return boolean true|false
	 */
	public function isDemoApps(): bool
	{
		$appType = Yii::$app->setting->get(join('_', [Yii::$app->id, 'app_type']), 'company');

		return $appType == 'demo-app' ? true : false;
	}

	/**
	 * Memeriksa apakah aplikasi berjalan pada mode demo theme
	 *
	 * @return boolean true|false
	 */
	public function isDemoTheme(): bool
	{
		$appType = Yii::$app->setting->get(join('_', [Yii::$app->id, 'app_type']), 'company');

		return $appType == 'demo-theme' ? true : false;
	}

    /**
     * Memeriksa apakah aplikasi berjalan pada mode maintenance
     *
     * @return boolean true|false
     */
    public function isMaintenance(): bool
    {
        $app = Yii::$app->id;
        $online = Yii::$app->setting->get(join('_', [$app, 'online']), 1);

        if ($online == 1) {
            return false;
        }

        $constructionDate = Yii::$app->setting->get(join('_', [$app, 'construction_date']));
        $isOnline = $constructionDate < TimeHelper::getDate() ? 1 : 0;

        return (!$isOnline && (Yii::$app->user->isGuest || (!Yii::$app->user->isGuest && !in_array(Yii::$app->user->identity->level_id, [1,2]))));
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
			if ($route == $r) {
				return true;
            }
		}
		return false;
	}

	/**
	 * Mengembalikan defaultRoute apps
	 *
	 * @return string
	 */
	public function isDefaultRoute(): bool
	{
		$app = $this->id;
		$module = $this->controller->module->id;

		if ($module == $app) {
			$controller = $this->controller->id;
			$action = $this->controller->action->id;
			return ($controller == 'site' && $action == 'index') ? true : false;
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
				if ($k == 'class' && $items == 'yii\db\Connection') {
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
