<?php
/**
 * View class
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 10 December 2017, 14:00 WIB
 * @link https://github.com/ommu/ommu2
 * 
 */

namespace app\components;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

class View extends \yii\web\View
{
	use \ommu\traits\UtilityTrait;
	use \ommu\traits\ThemeTrait;
	use \ommu\traits\FileTrait;
	use \app\modules\user\components\traits\UserTrait;

	/**
	 * {@inheritdoc}
	 */
	public $cards = true;
	/**
	 * @var string tempat menyimpan deskripsi pada controller yang akan ditampilkan view/layout sebagai meta description.
	 */
	public $description;
	/**
	 * @var string tempat menyimpan keyword pada controller yang akan ditampilkan view/layout sebagai meta keyword.
	 */
	public $keywords;
	/**
	 * @var string tempat menyimpan lokasi image pada controller yang akan ditampilkan view/layout sebagai meta image.
	 */ 
	public $image;
	/**
	 * @var string tempat menyimpan url background pada controller yang akan ditampilkan view/layout sebagai halaman belakang dialog.
	 */ 
	public $background;
	/**
	 * @var boolean tempat menyimpan status show|hide title pada controller yang akan ditampilkan view/layout saat render.
	 */
	public $titleShow = true;
	/**
	 * @var boolean tempat menyimpan status show|hide description pada controller yang akan ditampilkan view/layout saat render.
	 */
	public $descriptionShow = false;
	/**
	 * @var boolean tempat menyimpan status show|hide sidebar pada controller yang akan ditampilkan view/layout saat render.
	 */
	public $sidebarShow = false;
	/**
	 * @var boolean tempat menyimpan status show|hide search pada controller yang akan ditampilkan view/layout saat render.
	 */
	public $searchShow = false;
	/**
	 * @var array tempat menyimpan pengaturan aplikasi berdasarkan tema yanng digunakan
	 */
	public $themeSetting = [];
	/**
	 * @var boolean tempat menyimpan tipe current controller sebagai back-office atau front-office.
	 *     default false, artinya current controller bertipe front-office.
	 */
	public static $isBackoffice = false;
	/**
	 * @var boolean tempat menyimpan status untuk mencegah fungsi seting pada controller dipangil berulang kali.
	 */
	private static $_settingInitialize = false;
	/**
	 * @var boolean tempat menyimpan status pengaturan pada tema untuk mencegah fungsi dipangil berulang kali.
	 */
	private static $_themeSettingApplied = false;
	/**
	 * @var boolean tempat menyimpan status untuk mencegah fungsi seting tema dipangil berulang kali.
	 */
	private static $_themeApplied = false;
	/**
	 * @var boolean tempat menyimpan nama aplikasi untuk mencegah fungsi dipangil berulang kali.
	 */
	private static $_appNameApplied = false;
	/**
	 * @var boolean tempat menyimpan status banned dengan ip address untuk mencegah fungsi pengecekan ip address dipangil berulang kali.
	 */
	private static $_bannedIpApplied = false;
	/**
	 * @var boolean tempat menyimpan status untuk mencegah fungsi generate tokenAuthSocket pada controller dipangil berulang kali.
	 */
	private static $_authSocketInitialize = false;
	/**
	 * {@inheritdoc}
	 */
	private static $_beforeRenderEventCalled = 0;

	/**
	 * beforeRender()
	 * Menetapkan tema yang digunakan berdasarkan current controller.
	 */
	public function beforeRender($viewFile, $params)
	{
		if (parent::beforeRender($viewFile, $params)) {
            $context = $this->context;

            // Theme applied
            if (!self::$_themeApplied && !$this->theme) {
                self::$_themeApplied = true;
                $this->setTheme($context);
            }

            // Theme setting applied
            if (!self::$_themeSettingApplied) {
                $this->setThemeSetting();
                self::$_themeSettingApplied = true;
            }

            // Application name applied
			if (!self::$_appNameApplied) {
				$app = Yii::$app->id;
				$siteName = unserialize(Yii::$app->setting->get(join('_', [$app, 'name'])));
				Yii::$app->name = $siteName ? $siteName['small'] : 'OMMU';

				if (Yii::$app->isDemoTheme()) {
					$themeInfo = self::themeInfo($this->theme->name);
					Yii::$app->name = Inflector::camelize($themeInfo['name']);
				}
				self::$_appNameApplied = true;
			}

            // Setting initialize
            if (!self::$_settingInitialize) {
                self::$_settingInitialize = true;
            }

            if ($context instanceof Controller) {
                // Banned with IP address
                if (!self::$_bannedIpApplied) {
                    self::$_bannedIpApplied = true;

                    if ($context->hasMethod('isVisitorBanned')) {
                        if ($context->isVisitorBanned() === true) {
                            if (file_exists($this->theme->getPath(join('/', ['layouts', 'block.php'])))) {
                                $context->layout = 'block';
                            } else {
                                $context->layout = 'main';
                            }

                            return true;

                        } else {
                            // Back3nd submenu
                            if (self::$isBackoffice && (!empty($context->subMenu) && !Yii::$app->request->isAjax)) {
                                $context->layout = 'main_submenu';
                            }
                
                            // Front3nd sidebar
                            if (!self::$isBackoffice && ($this->sidebarShow && !Yii::$app->request->isAjax)) {
                                $context->layout = 'main_sidebar';
                            }
                        }
                    }
                }

                // Google analytics regitered
                $this->registerGoogleAnalytics();

                // webSocket
                if (!self::$_authSocketInitialize) {
                    self::$_authSocketInitialize = true;

                    !Yii::$app->request->isAjax ? \app\assets\CentrifugeAsset::register($this) : '';
                    if (Yii::$app->broadcaster->isEnable() === true) {
                        $userId = !Yii::$app->user->isGuest ? Yii::$app->user->id : 'isGuest';
                        $centrifugeToken = Yii::$app->broadcaster->getToken($userId);
                        $centrifugeHost = Yii::$app->params['broadcaster']['server'];
                        $centrifugePort = Yii::$app->params['broadcaster']['port'];
$js = <<<JS
    const centrifuge = new Centrifuge('ws://{$centrifugeHost}:{$centrifugePort}/connection/websocket', {
        token: '{$centrifugeToken}'
    });

    centrifuge.on('connecting', function (ctx) {
        console.log('connecting: ' + ctx.code + ', ' + ctx.reason);
    }).on('connected ', function (ctx) {
        console.log('connected over' + ctx.transport);
    }).on('disconnected ', function (ctx) {
        console.log('disconnected:' + ctx.code+ ', ' +ctx.reason);
    }).connect();
JS;
!Yii::$app->request->isAjax ? $this->registerJs($js, $this::POS_END) : '';
                    }
                }
            }
		}

		return true;
	}

	/**
	 * afterRender()
	 * Mendaftarkan meta description, keyword dan image pada view/layout saat render.
	 */
	public function afterRender($viewFile, $params, &$output)
	{
		$app = Yii::$app->id;
		$description = Yii::$app->setting->get(join('_', [$app, 'description']));
		$keywords = Yii::$app->setting->get(join('_', [$app, 'keywords']));
		$backendIndexing = Yii::$app->setting->get(join('_', [$app, 'backoffice_indexing']), 1);
		$frontendIndexing = Yii::$app->setting->get(join('_', [$app, 'theme_indexing']), 1);

		parent::afterRender($viewFile, $params, $output);

		$this->unsetAssetBundles();

		Yii::$app->meta->setTitle(Yii::$app->isDefaultRoute() ? $this->pageTitle : $this->title);
		Yii::$app->meta->setDescription(trim($this->description) != '' ? $this->description : $description);
		Yii::$app->meta->setKeywords(trim($this->keywords) != '' ? join(', ', [$this->keywords, $keywords]) : $keywords);
		Yii::$app->meta->setImage($this->image);
		Yii::$app->meta->setUrl(Yii::$app->request->absoluteUrl);

		if ((self::$isBackoffice && !$backendIndexing) || (!self::$isBackoffice && !$frontendIndexing)) {
			Yii::$app->meta->setRobots('noindex');
        }
	}

	/**
	 * {@inheritdoc}
	 */
	public function renderAjaxContent($content)
	{
		ob_start();
		ob_implicit_flush(false);

		$this->beginPage();
		$this->head();
		$this->beginBody();
		echo $content;
		$this->endBody();
		$this->endPage(true);

		return ob_get_clean();
	}

	/**
	 * Mengembalikan pageTitle halaman saat render.
	 *
	 * @return string
	 */
	public function getPageTitle()
	{
		$app = Yii::$app->id;
		$pageTitleTemplate = Yii::$app->setting->get(join('_', [$app, 'pagetitle_template']), '{title} | {small-name} - {long-name}');
		$siteName = unserialize(Yii::$app->setting->get(join('_', [$app, 'name'])));

		$title = trim($this->title) != '' ? $this->title : 'OMMU';
		$this->title = $title;
		if (Yii::$app->isDemoTheme()) {
			$themeInfo = self::themeInfo($this->theme->name);
			if ($title != 'OMMU') {
				$title = join(' - ', [$this->title, $themeInfo['name']]);
            } else {
				$title = $themeInfo['name'];
            }
		}

		if (Yii::$app->isDefaultRoute()) {
			$pageTitleTemplate = str_replace('{title} | ', '', $pageTitleTemplate);
        }

		return strtr($pageTitleTemplate, [
			'{title}' => $title,
			'{small-name}' => $siteName ? $siteName['small'] : 'OMMU',
			'{long-name}' => $siteName ? $siteName['long'] : 'OMMU',
		]);
	}

	/**
	 * Memeriksa status dialog sebuah halaman saat render.
	 *
	 * @return boolean true|false
	 */
	public function getDialog()
	{
		return isset($this->background) && trim($this->background) != '' ? true : false;
	}

	/**
	 * Mengembalikan cache versi pada url saat mendaftarkan javascript/css sebagai assets
	 *
	 * @param string $url
	 * @return string url yg sudah ditambah dengan versi
	 */
	protected function addCacheBustQuery($url)
	{
		if (strpos($url, '?') === false) {
			$file = str_replace('@web', '@webroot', $url);
			$file = Yii::getAlias($file);

			if (file_exists($file)) {
				$url .= '?v=' . filemtime($file);
            } else {
				$url .= '?v=' . urlencode(Yii::$app->version);
            }
		}
		return $url;
	}

	/**
	 * Mengembalikan nama (folder) tema yang sedang digunakan/aktif
	 * 
	 * @return string nama (folder) tema
	 */
	public function getCurrentTheme()
	{
		$themes = $this->theme->pathMap;
		$res = '';
		foreach($themes as $key => $val) {
			if ($key == '@app/views') {
				$res = $val;
				break;
			}
		}
		$t = explode('/', $res);
		return $t[count($t)-1];
	}

	/**
	 * Mengembalikan nama (alias) tema yang sedang digunakan/aktif
	 * 
	 * @return string nama (alias) tema
	 */
	public function getCurrentAliasTheme()
	{
		$alias = sprintf('@themes/%s', $this->getCurrentTheme());

		return $alias;
	}

	/**
	 * Menetapkan tema yang akan digunakan/aktif berdasarkan current controller.
	 * 
	 * @see beforeRender()
	 */
	public function setTheme($context): void
	{
        $installed = Yii::$app->params['installed'] ?? null;
        $databaseInstalled = Yii::$app->params['databaseInstalled'] ?? null;
		if (($installed && $installed === false) || ($databaseInstalled && $databaseInstalled === false)) {
			return;
        }

		$app = Yii::$app->id;
		$isBackofficeTheme = true;
		if ($context != null && $context->hasMethod('isBackofficeTheme')) {
			$isBackofficeTheme = $context->isBackofficeTheme();
        }

		$themeParam = join('_', [$app, 'theme']);
		$themeName = Yii::$app->setting->get($themeParam, Yii::$app->params['defaultTheme']);
		if ($isBackofficeTheme) {
			$themeParam = join('_', [$app, 'backoffice_theme']);
			$themeName = Yii::$app->setting->get($themeParam, Yii::$app->params['defaultTheme']);
			self::$isBackoffice = true;
		}

		$this->theme($themeName);
	}

	/**
	 * Menetapkan pengaturan tema
	 */
	public function theme($themeName): void
	{
		$app = Yii::$app->id;
		$this->theme = new \app\components\Theme([
			'basePath'	=> sprintf('@themes/%s', $themeName),
			'baseUrl'	=> sprintf('@web/themes/%s', $themeName),
			'pathMap'	=> [
				'@app/views'	=> sprintf('@themes/%s', $themeName),
				'@app/modules'	=> sprintf('@themes/%s/modules', $themeName),
				'@app/widgets'	=> sprintf('@themes/%s/widgets', $themeName),
				sprintf('@%s/app/views', $app)		=> sprintf('@themes/%s', $themeName),
				sprintf('@%s/app/modules', $app)	=> sprintf('@themes/%s/modules', $themeName),
				sprintf('@%s/app/widgets', $app)	=> sprintf('@themes/%s/widgets', $themeName),
			],
		]);
	}

	/**
	 * Menetapkan sub-layout dari tema yang akan digunakan/aktif berdasarkan current controller.
	 */
	public function getSublayout($subLayout='default')
	{
		if (($layout = Yii::$app->request->get('layout')) != null) {
			return $layout ? $layout : $subLayout;
        }

		$app = Yii::$app->id;
		$themeSublayout = Yii::$app->setting->get(join('_', [$app, 'theme_sublayout']), $subLayout);
		if (self::$isBackoffice) {
			$themeSublayout = Yii::$app->setting->get(join('_', [$app, 'backoffice_theme_sublayout']), $subLayout);
        }

		return $themeSublayout ? $themeSublayout : $subLayout;
	}

	/**
	 * Menghapus register asset pada assetBundles yang terdapat pada ignore_asset_class
	 */
	public function unsetAssetBundles()
	{
		$ignoreAssetClass = $this->themeSetting['ignore_asset_class'] ?? null;

		if ($ignoreAssetClass && is_array($ignoreAssetClass) && !empty($ignoreAssetClass)) {
			foreach ($ignoreAssetClass as $assetClass) {
				unset($this->assetBundles[$assetClass]);
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function setThemeSetting()
	{
        $themeInfo = self::themeInfo($this->theme->name);

        $themeSetting = [];
        if (is_array($themeInfo) && array_key_exists('theme_setting', $themeInfo)) {
            $themeSetting = ArrayHelper::merge($themeSetting, $themeInfo['theme_setting']);
        }
        if (is_array($themeInfo) && array_key_exists('widget_class', $themeInfo)) {
            $themeSetting = ArrayHelper::merge($themeSetting, ['widget_class' => $themeInfo['widget_class']]);
        }
        if (is_array($themeInfo) && array_key_exists('ignore_asset_class', $themeInfo)) {
            $themeSetting = ArrayHelper::merge($themeSetting, ['ignore_asset_class' => $themeInfo['ignore_asset_class']]);
        }

        $this->themeSetting = $themeSetting;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPagination()
	{
		$app = Yii::$app->id;
		$themePagination = Yii::$app->setting->get(join('_', [$app, 'theme_pagination']), 'default');
		if (self::$isBackoffice) {
			$themePagination = Yii::$app->setting->get(join('_', [$app, 'backoffice_theme_pagination']), 'default');
        }

		return $themePagination ? $themePagination : 'default';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSubmenuOnLayout()
	{
		return !empty($this->context->subMenu) ? true : false;
	}

	/**
	 * Renders a static string by applying a widget layout.
	 *
	 * @param string $view the view name.
	 * @param array $params the parameters (name-value pairs) that should be made available in the view.
	 * These parameters will not be available in the layout.
	 * @return string the rendering result.
	 */
	public function renderWidget($view, $params=[], $context=null)
	{
		if ($context == null) {
			$context = $this->context;
        }

		if (isset($params['overwrite']) && $params['overwrite'] == true) {
			$content = $view;
        } else {
			$content = $this->render($view, $params, $context);
        }

		$cards = true;
		if (isset($params['cards']) && $params['cards'] == false) {
			$cards = false;
        }
		if ($cards == false) {
			return $content;
        }

		$layout = $context->layout ? $context->layout : 'main';
		$layoutFile = preg_replace("/($layout)/", 'widget', $context->findLayoutFile($this));
		if ($layoutFile !== false) {
            $contentParams = ['content' => $content];
            
            // widget title condition
            $pageId = $params['pageId'] ?? false;
            $contentParams = ArrayHelper::merge($contentParams, ['pageId' => $pageId]);
            
            // widget title condition
            $title = $params['title'] ?? false;
            $contentParams = ArrayHelper::merge($contentParams, ['title' => $title]);

            // padding body condition
            $paddingBody = $params['paddingBody'] ?? true;
            $contentParams = ArrayHelper::merge($contentParams, ['paddingBody' => $paddingBody]);

            // text align condition
            $textAlign = $params['textAlign'] ?? false;
            $contentParams = ArrayHelper::merge($contentParams, ['textAlign' => $textAlign]);

            // content menu condition
            $contentMenu = $params['contentMenu'] ?? false;
            $contentParams = ArrayHelper::merge($contentParams, ['contentMenu' => $contentMenu]);

            // alert condition
            $alert = $params['alert'] ?? true;
            $contentParams = ArrayHelper::merge($contentParams, ['alert' => $alert]);

			return $this->renderFile($layoutFile, $contentParams, $context);
		}

		return $content;
	}

	/**
	 * {@inheritdoc}
	 */
	public function registerGoogleAnalytics()
	{
		$app = Yii::$app->id;
		$analytic = Yii::$app->setting->get(join('_', [$app, 'analytic']), 1);
		$analytic_property = Yii::$app->setting->get(join('_', [$app, 'analytic_property']), '');

		if (!Yii::$app->isDev() && $analytic && $analytic_property) {
			$this->registerJsFile('https://www.googletagmanager.com/gtag/js?id='.$analytic_property, ['position' => self::POS_END, 'async' => 'async']);
$js = <<<JS
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', '{$analytic_property}');
JS;
			$this->registerJs($js, self::POS_END);
		}
	}

	/**
	 * Renders a static string by applying a wizard layout.
	 *
	 * @param string $view the view name.
	 * @param array $params the parameters (name-value pairs) that should be made available in the view.
	 * These parameters will not be available in the layout.
	 * @return string the rendering result.
	 */
	public function renderWizard($view, $params=[], $context=null)
	{
		if ($context == null) {
            $context = $this->context;
        }

        $content = $this->render($view, $params, $context);

		$layout = $context->layout ? $context->layout : 'main';
		$layoutFile = preg_replace("/($layout)/", 'wizard', $context->findLayoutFile($this));
		if ($layoutFile !== false) {
            $contentParams = ['content' => $content];
            
            // wizard navigation condition
            $navigation = $params['navigation'] ?? '';
            $contentParams = ArrayHelper::merge($contentParams, ['navigation' => $navigation]);

            $current = $params['current'] ?? '';
            $contentParams = ArrayHelper::merge($contentParams, ['current' => $current]);

			return $this->renderFile($layoutFile, $contentParams, $context);
		}

		return $content;
	}
}