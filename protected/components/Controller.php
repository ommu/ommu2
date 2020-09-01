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
	 * @var boolean tempat menyimpan status untuk mencegah fungsi seting tema dipangil berulang kali.
	 */
	private static $_themeApplied = false;

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
		if(parent::beforeAction($action)) {
			if(!self::$settingInitialize)
				self::$settingInitialize = true;

			if($action instanceof \yii\web\ErrorAction) {
				$model = \ommu\report\models\ReportSetting::find()
					->select(['auto_report_cat_id'])
					->where(['id'=>1])
					->one();
				
				if($model->auto_report_i) {
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

		if(!self::$_themeApplied) {
			self::$_themeApplied = true;
			$this->getView()->setTheme($this);
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

		$data = ArrayHelper::merge(
			$data,
			['partial' => Yii::$app->request->isAjax ? true : false]
		);

		if(!Yii::$app->request->isAjax || (Yii::$app->request->isAjax && Yii::$app->request->get('_pjax')))
			return $this->render($render, $data);

		return $this->renderModal($render, ArrayHelper::merge($data, ['modalHeader'=>false]));
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

		$layout = $context->layout ? $context->layout : 'main';
		$layoutFile = preg_replace("/($layout)/", 'modal', $this->findLayoutFile($this->getView()));
		if ($layoutFile !== false) {
			$contentParams = ['content'=>$content];

			$modalHeader = true;
			if(isset($params['modalHeader']))
				$modalHeader = $params['modalHeader'];
			$contentParams = ArrayHelper::merge($contentParams, ['modalHeader'=>$modalHeader]);

			return $this->getView()->renderFile($layoutFile, $contentParams, $this);
		}

		return $content;
	}
}
