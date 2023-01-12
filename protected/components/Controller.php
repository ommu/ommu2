<?php
/**
 * Controller class
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 20 December 2017, 18:21 WIB
 * @link https://github.com/ommu/ommu
 */

namespace app\components;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

class Controller extends \yii\web\Controller
{
	use \ommu\traits\UtilityTrait;

	/**
	 * {@inheritdoc}
	 */
	public $breadcrumbApp = false;
	/**
	 * {@inheritdoc}
	 */
	public $breadcrumbAppParam = [];
	/**
	 * @var string untuk menampung sub-menu yang akan di render pada layout main_submenu.
	 */
	public $subMenu = [];
	/**
	 * @var integer untuk menampung sub-menu parameter.
	 */
	public $subMenuParam;
	/**
	 * @var integer untuk menampung sub-menu parameter.
	 */
	public $subMenuBackTo;
	/**
	 * @var object instance dari current controller. jika controller yg akses site maka akan berisi site.
	 *    variabel akan di isi oleh klas anak/turunan dari klas ini.
	 */
	public static $controller;
	/**
	 * @var array berisi nama action yang boleh diakses tanpa authentikasi. variabel ini akan di isi
	 *    pada fungsi init dengan nilai yg diambil dari tabel public_route dan|atau didefinisikan pada controller.
	 */
	public static $allowedAction = [];
	/**
	 * @var boolean berisi status yang akan menentukan apakah controller/action ini akan memakai tema back office
	 *    atau front office. default true artinya tema backoffice yg akan dipakai.
	 */
	public static $backoffice = true;
	/**
	 * @var boolean tempat menyimpan status banned visitor berdasarkan ip address.
	 */
    public static $visitorBanned = false;
	/**
	 * {@inheritdoc}
	 * @var string nama model untuk hapus data(real) untuk keperluan testing.
	 */
	public static $modelClass = null;
	/**
	 * @var boolean tempat menyimpan status untuk mencegah fungsi seting pada controller dipangil berulang kali.
	 */
	private static $settingInitialize = false;
	/**
	 * @var boolean tempat menyimpan status untuk mencegah fungsi seting tema dipangil berulang kali.
	 */
	private static $_themeApplied = false;
	/**
	 * @var boolean tempat menyimpan status banned dengan ip address untuk mencegah fungsi pengecekan ip address dipangil berulang kali.
	 */
	private static $_bannedIpApplied = false;

	/**
	 * {@inheritdoc}
	 */
	public function renderAjaxContent($content)
	{
		return $this->getView()->renderAjaxContent($content, $this);
	}

	/**
	 * {@inheritdoc}
	 */
	public function htmlRedirect($url='')
	{
		return $this->renderPartial('@app/views/htmlRedirect.php', [
			'url' => Url::to($url),
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function renderModalClose()
	{
		return $this->renderPartial('@app/views/modalClose.php', []);
	}

	/**
	 * {@inheritdoc}
	 */
	public function beforeAction($action) 
	{
        if (parent::beforeAction($action)) {
            // Setting initialize
            if (!self::$settingInitialize) {
                self::$settingInitialize = true;
            }

            // Auto reported
            if ($action instanceof \yii\web\ErrorAction) {
                $model = \ommu\report\models\ReportSetting::find()
                    ->select(['auto_report_cat_id'])
                    ->where(['id' => 1])
                    ->one();
                
                if ($model->autoReport) {
                    $url = Yii::$app->request->absoluteUrl;
                    $name = $action->getExceptionName();
                    $message = $action->getExceptionMessage();
                    $message = $name.' '.nl2br(Html::encode($message));
                    \ommu\report\models\Reports::insertReport($url, $message);
                }
            }
        }

		return true;
	}

	/**
	 * Isikan variabel $controller dengan instansi dari controller anak.
	 * Reset $allowedAction dengan array kosong.
	 * Menambahkan $allowedAction dengan query public route berdasarkan current controller.
	 *
	 * TODO: optimalkan jika public route banyak sekali!. cache misalnya
	 */
	public function init() 
	{
		self::$controller = $this;
		self::$allowedAction = [];
		parent::init(); 
		
		$controller = self::$controller;
		/*
		$routes  = PublicRoute::find()->orderBy('route ASC')->all();
		$currentRoute = $this->getRoute();
		foreach ($routes as $val) {
			$action  = $this->getAllowedAction($currentRoute, $val->route);
			if ($action != null) {
				array_push(self::$allowedAction, $action);
			}
		}
		*/

        // Theme applied
		if (!self::$_themeApplied) {
			$this->getView()->setTheme($this);
			self::$_themeApplied = true;
        }

        // Banned with IP address
		if (!self::$_bannedIpApplied) {
            self::$visitorBanned = $this->bannedWithIps();
			self::$_bannedIpApplied = true;
        }
	}

	/**
	 * Get action yg cocok dengan rute di database
	 *
	 * @param  string $currentRoute rute saat ini
	 * @param  string $dbRoute      rute pada tabel
	 * @return mixed  string|null
	 */
	private function getAllowedAction(string $currentRoute, string $dbRoute): ?string
	{
		$route      = trim($dbRoute);
		$listRoute  = explode('/', $route);
		$routeCnt   = count($listRoute);
		$action     = $listRoute[$routeCnt - 1];
		$matchRoute = array_slice($listRoute, 0, $routeCnt - 1);
		$_route     = implode('/', $matchRoute);

		if ($currentRoute == $_route) {
			return $action;
		}
		return null;
	}

	/**
	 * Menentukan action yang boleh diakses tanpa authentikasi.
	 * 
	 * @see init()
	 */
	public function allowAction(): array 
	{
		return self::$allowedAction;
	}

	/**
	 * Menentukan setingan tema yang digunakan (back-office/front-office) pada current controller.
	 *
	 * @return boolean true|false
	 */
	public function isBackofficeTheme(): bool
	{
		return static::$backoffice;
	}

	/**
	 * Menentukan balikan hak akses visitor pada aplikasi berdasarkan ip address.
     *  function ini digunakan pada class \app\components\Views
	 *
	 * @return boolean true|false
	 */
	public function isVisitorBanned(): bool
	{
		return static::$visitorBanned;
	}

	/**
	 * Mengembalikan render dalam bentuk partial atau bukan partial.
	 *
	 * @param string $view
	 * @param array $params
	 * @return mixed
	 */
	public function oRender($view, $params=null)
	{
		if ($params == null) {
			$params = [];
        }

		$params = ArrayHelper::merge(
			$params,
			['partial' => Yii::$app->request->isAjax ? true : false]
		);

		if (!Yii::$app->request->isAjax || (Yii::$app->request->isAjax && Yii::$app->request->get('_pjax'))) {
			return $this->render($view, $params);
        }

		return $this->renderModal($view, ArrayHelper::merge($params, ['modalHeader' => false]));
	}

	/**
	 * Renders a static string by applying a modal layout.
	 *
	 * @param string $view the view name.
	 * @param array $params the parameters (name-value pairs) that should be made available in the view.
	 * These parameters will not be available in the layout.
	 * @return string the rendering result.
	 */
	public function renderModal($view, $params=[])
	{
		$content = $this->renderAjax($view, $params);

		$layout = $this->layout ?? 'main';
		$layoutFile = preg_replace("/($layout)/", 'modal', $this->findLayoutFile($this->getView()));
		if ($layoutFile !== false) {
			$contentParams = ['content' => $content];

			$modalHeader = true;
			if (isset($params['modalHeader'])) {
				$modalHeader = $params['modalHeader'];
            }
			$contentParams = ArrayHelper::merge($contentParams, ['modalHeader' => $modalHeader]);

			return $this->getView()->renderFile($layoutFile, $contentParams, $this);
		}

		return $content;
	}

	/**
	 * {@inheritdoc}
	 */
	public function bannedWithIps(): bool
	{
		$setting = \app\models\CoreSettings::find()
			->select(['banned_ips'])
			->where(['id' => 1])
			->one();

        $visitorIsBanned = false;

        if ($setting->banned_ips != '') {
            $bannedIps = $this->strToArray($setting->banned_ips, ':');
            $allowStatus = $bannedIps[0] == strtolower('allow') ? true : false;
            if ($allowStatus === true) {
                unset($bannedIps[0]);
                $bannedIps = array_values($bannedIps);
            }
            $visitorIsBanned = $allowStatus === true ? true : false;
            $visitorIp = $this->getUserIP();
            $bannedIps = $this->strToArray($bannedIps[0]);

            if (in_array($visitorIp, $bannedIps)) {
                $visitorIsBanned = $allowStatus === true ? false : true;
            } else {
                foreach($bannedIps as $ip) {
                    if (strpos($ip, '*') !== false) {
                        if(strpos($visitorIp, array_shift(explode("*", $ip))) === 0) {
                            $visitorIsBanned = $allowStatus === true ? false : true;
                        }
                    } else {
                        if(strcmp($visitorIp, $ip) === 0) {
                            $visitorIsBanned = $allowStatus === true ? false : true;
                        }
                    }
                }
            }
        }

        return $visitorIsBanned;
	}
}
