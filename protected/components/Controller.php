<?php
/**
 * Controller class
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 20 December 2017, 18:21 WIB
 * @link https://github.com/ommu/ommu
 */

namespace app\components;

use Yii;
use yii\helpers\Url;

class Controller extends \yii\web\Controller
{
	/**
	 * {@inheritdoc}
	 * @var string untuk menampung sub-layout pada view.
	 */
	public $subLayout;
	/**
	 * @var string tempat menyimpan pageTitle pada controller yang akan ditampilkan view/layout sebagai nama halaman.
	 */
	public $pageTitle;
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
	 * {@inheritdoc}
	 * @var string nama model untuk hapus data(real) untuk keperluan testing.
	 */
	public static $modelClass = null;
	/**
	 * @var boolean tempat menyimpan status untuk mencegah fungsi seting pada controller dipangil berulang kali.
	 */
	private static $settingInitialize = false;

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
	public function forcePostRequest() 
	{
		if(\Yii::$app->request->method != 'POST') {
			echo 'Invalid method!';
			die();
		}
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
		if(parent::beforeAction($action)) {
			if(!self::$settingInitialize) {
				self::$settingInitialize = true;

				$setting = \app\models\CoreSettings::find()
				->select(['site_title'])
				->where(['id' => 1])
				->one();

				if($setting != null)
					Yii::$app->name = $setting->site_title;
			}

			if(!empty($this->pageTitle))
				$this->getView()->pageTitle = $this->pageTitle;

			return true;
		}
		return false;
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
		foreach($routes as $val) {
			$route = trim($val->route);
			if(substr($route, 0, 1) == '/') {
				$route = substr($route, 1);
			}
			$route = explode('/', $route);

			// module/controller/action
			if(count($route) == 3) {
				list($module, $controller, $action) = $route;
				if(Yii::$app->hasModule($module) == false)
					continue;
				else {
					array_push(self::$allowedAction, $action);
				}

			// controller/action
			}else {
				list($controller, $action) = $route;
				array_push(self::$allowedAction, $action);
			}
		}
		*/
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
	 * Mengembalikan render dalam bentuk partial atau bukan partial.
	 *
	 * @param string $render
	 * @param array $data
	 * @return mixed
	 */
	public function oRender($render, $data=null)
	{
		if($data == null)
			$data = [];

		$data = \yii\helpers\ArrayHelper::merge(
			$data, 
			['partial' => Yii::$app->request->isAjax ? true : false],
		);

		if(!Yii::$app->request->isAjax)
			return $this->render($render, $data);
		else
			return $this->renderPartial($render, $data);
	}
}
