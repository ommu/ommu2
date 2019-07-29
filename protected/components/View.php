<?php
/**
 * View class
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 10 December 2017, 14:00 WIB
 * @link https://github.com/ommu/ommu
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
	 * @var boolean tempat menyimpan tipe current controller sebagai back-office atau front-office.
	 *     default false, artinya current controller bertipe front-office.
	 */
	public static $isBackoffice = false;
	/**
	 * @var boolean tempat menyimpan status untuk mencegah fungsi seting pada controller dipangil berulang kali.
	 */
	private static $_settingInitialize = false;
	/**
	 * @var boolean tempat menyimpan status untuk mencegah fungsi seting tema dipangil berulang kali.
	 */
	private static $_themeApplied = false;
	/**
	 * @var boolean tempat menyimpan nama aplikasi untuk mencegah fungsi dipangil berulang kali.
	 */
	private static $_appNameApplied = false;
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
		if(parent::beforeRender($viewFile, $params)) {
			if(!self::$_themeApplied && !$this->theme) {
				self::$_themeApplied = true;

				$this->setTheme($this->context);
			}

			if(!empty($this->context->subMenu) && !Yii::$app->request->isAjax)
				$this->context->layout = 'main_submenu';

			if(!self::$_appNameApplied) {
				self::$_appNameApplied = true;

				$siteName = unserialize(Yii::$app->setting->get(join('_', [Yii::$app->id, 'name'])));
				Yii::$app->name = $siteName ? $siteName['small'] : 'OMMU';

				if(Yii::$app->isDemoTheme()) {
					$themeInfo = self::themeParseYaml($this->theme->name);
					Yii::$app->name = Inflector::camelize($themeInfo['name']);
				}
			}

			if(!self::$_settingInitialize) {
				self::$_settingInitialize = true;
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
		$description = Yii::$app->setting->get(join('_', [Yii::$app->id, 'description']));
		$keywords = Yii::$app->setting->get(join('_', [Yii::$app->id, 'keywords']));
		$backendIndexing = Yii::$app->setting->get(join('_', [Yii::$app->id, 'backoffice_indexing']), 1);
		$frontendIndexing = Yii::$app->setting->get(join('_', [Yii::$app->id, 'theme_indexing']), 1);

		parent::afterRender($viewFile, $params, $output);

		$this->unsetAssetBundles();

		Yii::$app->meta->setTitle($this->title);
		Yii::$app->meta->setDescription(trim($this->description) != '' ? $this->description : $description);
		Yii::$app->meta->setKeywords(trim($this->keywords) != '' ? $this->keywords : $keywords);
		Yii::$app->meta->setImage($this->image);
		Yii::$app->meta->setUrl(Yii::$app->request->absoluteUrl);

		if((self::$isBackoffice && !$backendIndexing) || (!self::$isBackoffice && !$frontendIndexing))
			Yii::$app->meta->setRobots('noindex');
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
	 * Registers a JS file.
	 *
	 * This method should be used for simple registration of JS files. If you want to use features of
	 * [[AssetManager]] like appending timestamps to the URL and file publishing options, use [[AssetBundle]]
	 * and [[registerAssetBundle()]] instead.
	 *
	 * @param string $url the JS file to be registered.
	 * @param array $options the HTML attributes for the script tag. The following options are specially handled
	 * and are not treated as HTML attributes:
	 *
	 * - `depends`: array, specifies the names of the asset bundles that this JS file depends on.
	 * - `position`: specifies where the JS script tag should be inserted in a page. The possible values are:
	 *     * [[POS_HEAD]]: in the head section
	 *     * [[POS_BEGIN]]: at the beginning of the body section
	 *     * [[POS_END]]: at the end of the body section. This is the default value.
	 *
	 * Please refer to [[Html::jsFile()]] for other supported options.
	 *
	 * @param string $key the key that identifies the JS script file. If null, it will use
	 * $url as the key. If two JS files are registered with the same key at the same position, the latter
	 * will overwrite the former. Note that position option takes precedence, thus files registered with the same key,
	 * but different position option will not override each other.
	 */
	public function registerJsFile($url, $options=[], $key=null)
	{
		parent::registerJsFile($this->addCacheBustQuery($url), $options, $key);
	}

	/**
	 * Registers a CSS file.
	 *
	 * This method should be used for simple registration of CSS files. If you want to use features of
	 * [[AssetManager]] like appending timestamps to the URL and file publishing options, use [[AssetBundle]]
	 * and [[registerAssetBundle()]] instead.
	 *
	 * @param string $url the CSS file to be registered.
	 * @param array $options the HTML attributes for the link tag. Please refer to [[Html::cssFile()]] for
	 * the supported options. The following options are specially handled and are not treated as HTML attributes:
	 *
	 * - `depends`: array, specifies the names of the asset bundles that this CSS file depends on.
	 *
	 * @param string $key the key that identifies the CSS script file. If null, it will use
	 * $url as the key. If two CSS files are registered with the same key, the latter
	 * will overwrite the former.
	 */
	public function registerCssFile($url, $options=[], $key=null)
	{
		parent::registerCssFile($this->addCacheBustQuery($url), $options, $key);
	}

	/**
	 * Mengembalikan pageTitle halaman saat render.
	 *
	 * @return string
	 */
	public function getPageTitle()
	{
		$pageTitleTemplate = Yii::$app->setting->get(join('_', [Yii::$app->id, 'pagetitle_template']), '{title} | {small-name} - {long-name}');
		$siteName = unserialize(Yii::$app->setting->get(join('_', [Yii::$app->id, 'name'])));

		$title = trim($this->title) != '' ? $this->title : 'OMMU';
		$this->title = $title;
		if(Yii::$app->isDemoTheme()) {
			$themeInfo = self::themeParseYaml($this->theme->name);
			if($title != 'OMMU')
				$title = join(' - ', [$this->title, $themeInfo['name']]);
			else
				$title = $themeInfo['name'];
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
		if(strpos($url, '?') === false) {
			$file = str_replace('@web', '@webroot', $url);
			$file = Yii::getAlias($file);

			if(file_exists($file))
				$url .= '?v=' . filemtime($file);
			else
				$url .= '?v=' . urlencode(Yii::$app->version);
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
			if($key == '@app/views') {
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
		if(Yii::$app->params['installed'] === false || Yii::$app->params['databaseInstalled'] === false)
			return;

		$isBackofficeTheme = true;
		if($context != null && $context->hasMethod('isBackofficeTheme'))
			$isBackofficeTheme = $context->isBackofficeTheme();

		$themeParam = join('_', [Yii::$app->id, 'theme']);
		$themeName = Yii::$app->setting->get($themeParam, Yii::$app->params['defaultTheme']);
		if($isBackofficeTheme) {
			$themeParam = join('_', [Yii::$app->id, 'backoffice_theme']);
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
		$this->theme = new \app\components\Theme([
			'basePath'	=> sprintf('@themes/%s', $themeName),
			'baseUrl'	=> sprintf('@web/themes/%s', $themeName),
			'pathMap'	=> [
				'@app/views'	=> sprintf('@themes/%s', $themeName),
				'@app/modules'	=> sprintf('@themes/%s/modules', $themeName),
				'@app/widgets'	=> sprintf('@themes/%s/widgets', $themeName),
				sprintf('@%s/app/views', Yii::$app->id)		=> sprintf('@themes/%s', $themeName),
				sprintf('@%s/app/modules', Yii::$app->id)	=> sprintf('@themes/%s/modules', $themeName),
				sprintf('@%s/app/widgets', Yii::$app->id)	=> sprintf('@themes/%s/widgets', $themeName),
			],
		]);
	}

	/**
	 * Menetapkan sub-layout dari tema yang akan digunakan/aktif berdasarkan current controller.
	 */
	public function getSublayout()
	{
		if(($layout = Yii::$app->request->get('layout')) != null)
			return $layout ? $layout : 'default';

		$themeSublayout = Yii::$app->setting->get(join('_', [Yii::$app->id, 'theme_sublayout']), 'default');
		if(self::$isBackoffice)
			$themeSublayout = Yii::$app->setting->get(join('_', [Yii::$app->id, 'backoffice_theme_sublayout']), 'default');

		return $themeSublayout ? $themeSublayout : 'default';
	}

	/**
	 * Menghapus register asset pada assetBundles yang terdapat pada ignore_asset_class
	 */
	public function unsetAssetBundles()
	{
		$ignoreAssetClass = $this->themeSetting['ignore_asset_class'];

		if(isset($ignoreAssetClass) && is_array($ignoreAssetClass) && !empty($ignoreAssetClass)) {
			foreach ($ignoreAssetClass as $assetClass) {
				unset($this->assetBundles[$assetClass]);
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getThemeSetting()
	{
		$themeInfo = self::themeParseYaml($this->theme->name);

		$themeSetting = [];
		if(isset($themeInfo['theme_setting']))
			$themeSetting = ArrayHelper::merge($themeSetting, $themeInfo['theme_setting']);
		if(isset($themeInfo['widget_class']))
			$themeSetting = ArrayHelper::merge($themeSetting, ['widget_class'=>$themeInfo['widget_class']]);
		if(isset($themeInfo['ignore_asset_class']))
			$themeSetting = ArrayHelper::merge($themeSetting, ['ignore_asset_class'=>$themeInfo['ignore_asset_class']]);

		return $themeSetting;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPagination()
	{
		$themePagination = Yii::$app->setting->get(join('_', [Yii::$app->id, 'theme_pagination']), 'default');
		if(self::$isBackoffice)
			$themePagination = Yii::$app->setting->get(join('_', [Yii::$app->id, 'backoffice_theme_pagination']), 'default');

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
		if($context == null)
			$context = $this->context;
		$content = $this->render($view, $params, $context);

		$layout = $context->layout ? $context->layout : 'main';
		$layoutFile = preg_replace("/($layout)/", 'widget', $context->findLayoutFile($this));
		if ($layoutFile !== false) {
			$contentParams = ['content'=>$content];

			$contentMenu = false;
			if(isset($params['contentMenu']))
				$contentMenu = $params['contentMenu'];
			$contentParams = ArrayHelper::merge($contentParams, ['contentMenu'=>$contentMenu]);

			return $this->renderFile($layoutFile, $contentParams, $context);
		}

		return $content;
	}
}