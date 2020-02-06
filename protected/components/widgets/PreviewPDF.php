<?php
namespace app\components\widgets;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\assets\PdfJsAsset;

class PreviewPDF extends \yii\base\Widget
{
	public $waterMark = true;
	public $waterMarkParam = [];

	public $url;

	public $options = [];

	public $navigationOptions = [];

	public $previewOptions = [];

	public $navigationLayout = "{prev}\n{summary}\n{next}\n{zoomOut}\n{zoomIn}";
	public $layout = "{navigation}\n{preview}";

	public function init()
	{
		$waterMarkParam = [
			'text' => 'OMMU',
			'alpha' => '0.5',
			'color' => 'red',
		];
		if(isset($this->waterMarkParam) && !empty($this->waterMarkParam))
			$this->waterMarkParam = ArrayHelper::merge($waterMarkParam, $this->waterMarkParam);
		else
			$this->waterMarkParam = $waterMarkParam;

		if($this->waterMark == false)
			$this->waterMarkParam = [];

		// set default navigationOptions
		if(!isset($this->navigationOptions['class']))
			$this->navigationOptions['class'] = 'summary';

		if(!isset($this->navigationOptions['summary']))
			$this->navigationOptions['summary'] = [];

		if(!isset($this->navigationOptions['prev']))
			$this->navigationOptions['prev'] = [];

		if(!isset($this->navigationOptions['next']))
			$this->navigationOptions['next'] = [];

		if(!isset($this->navigationOptions['zoomIn']))
			$this->navigationOptions['zoomIn'] = [];

		if(!isset($this->navigationOptions['zoomOut']))
			$this->navigationOptions['zoomOut'] = [];

		// set default previewOptions
		if(!isset($this->previewOptions['class']))
			$this->previewOptions['class'] = 'preview-pdf';

		if(!isset($this->previewOptions['id']))
			$this->previewOptions['id'] = 'the-canvas';
	}

	protected function registerAssets()
	{
		$view = $this->getView();
		$pdfjsAsset = PdfJsAsset::register($view);
		$view->registerJsFile('http://mozilla.github.io/pdf.js/build/pdf.js', ['position' => $view::POS_END]);
		// $view->registerJsFile($pdfjsAsset->baseUrl . '/build/pdf.js', ['position' => $view::POS_END]);
$js = <<<JS
	var pdfjsAsset = '{$pdfjsAsset->baseUrl}';
	// If absolute URL from the remote server is provided, configure the CORS
	// header on that server.
	// var url = 'https://raw.githubusercontent.com/mozilla/pdf.js/ba2edeae/web/compressed.tracemonkey-pldi-09.pdf';
	var url = '{$this->url}';
JS;
		$view->registerJs($js, $view::POS_HEAD);

$jsFunction = <<<JS
	// Loaded via <script> tag, create shortcut to access PDF.js exports.
	var pdfjsLib = window['pdfjs-dist/build/pdf'];

	// The workerSrc property shall be specified.
	pdfjsLib.GlobalWorkerOptions.workerSrc = 'http://mozilla.github.io/pdf.js/build/pdf.worker.js';
	// pdfjsLib.GlobalWorkerOptions.workerSrc = pdfjsAsset + '/build/pdf.worker.js';

	var pdfDoc = null,
		pageNum = 1,
		pageRendering = false,
		pageNumPending = null,
		scale = 0.8,
		canvas = document.getElementById('the-canvas'),
		ctx = canvas.getContext('2d');

	/**
	 * Get page info from document, resize canvas accordingly, and render page.
	 * @param num Page number.
	 */
	function renderPage(num) {
		pageRendering = true;
		// Using promise to fetch the page
		pdfDoc.getPage(num).then(function(page) {
			var viewport = page.getViewport({scale: scale});
			canvas.height = viewport.height;
			canvas.width = viewport.width;

			// Render PDF page into canvas context
			var renderContext = {
				canvasContext: ctx,
				viewport: viewport
			};
			var renderTask = page.render(renderContext);

			// Wait for rendering to finish
			renderTask.promise.then(function() {
				pageRendering = false;
				if (pageNumPending !== null) {
					// New page rendering is pending
					renderPage(pageNumPending);
					pageNumPending = null;
				}
			});
		});

		// Update page counters
		document.getElementById('page_num').textContent = num;
	}

	/**
	 * If another page rendering in progress, waits until the rendering is
	 * finised. Otherwise, executes rendering immediately.
	 */
	function queueRenderPage(num) {
		if (pageRendering) {
			pageNumPending = num;
		} else {
			renderPage(num);
		}
	}

	/**
	 * Displays previous page.
	 */
	function onPrevPage() {
		if (pageNum <= 1) {
			return;
		}
		pageNum--;
		queueRenderPage(pageNum);
	}
	document.getElementById('prev').addEventListener('click', onPrevPage);

	/**
	 * Displays next page.
	 */
	function onNextPage() {
		if (pageNum >= pdfDoc.numPages) {
			return;
		}
		pageNum++;
		queueRenderPage(pageNum);
	}
	document.getElementById('next').addEventListener('click', onNextPage);

	/**
	 * Displays zoom (+) and (-).
	 */
	function onZoomIn() {
		scale = scale + 0.1;

		queueRenderPage(pageNum);
	}
	document.getElementById('zoomIn').addEventListener('click', onZoomIn);
	function onZoomOut() {
		scale = scale - 0.1;

		queueRenderPage(pageNum);
	}
	document.getElementById('zoomOut').addEventListener('click', onZoomOut);

	/**
	 * Asynchronously downloads PDF.
	 */
	pdfjsLib.getDocument(url)
		.promise
		.then(function(pdfDoc_) {
			pdfDoc = pdfDoc_;
			document.getElementById('page_count').textContent = pdfDoc.numPages;

			// Initial/first page rendering
			renderPage(pageNum);
		});
JS;
		$view->registerJs($jsFunction, $view::POS_END);
	}

	public function run() 
	{
		$this->registerAssets();
		$content = preg_replace_callback('/{\\w+}/', function ($matches) {
			$content = $this->renderSection($matches[0]);

			return $content === false ? $matches[0] : $content;
		}, $this->layout);

		$options = $this->options;
		$tag = ArrayHelper::remove($options, 'tag', 'div');
		return Html::tag($tag, $content, $options);
	}

	public function renderSection($name)
	{
		switch ($name) {
			case '{navigation}':
				return $this->renderNavigation();
			case '{preview}':
				return $this->renderPreview();
			default:
				return false;
		}
	}

	public function renderNavigation()
	{
		$content = preg_replace_callback('/{\\w+}/', function ($matches) {
			$content = $this->renderNavigationSection($matches[0]);

			return $content === false ? $matches[0] : $content;
		}, $this->navigationLayout);
		
		$navigationOptions = $this->navigationOptions;
		ArrayHelper::remove($navigationOptions, 'summary');
		ArrayHelper::remove($navigationOptions, 'prev');
		ArrayHelper::remove($navigationOptions, 'next');
		ArrayHelper::remove($navigationOptions, 'zoom');
		$tag = ArrayHelper::remove($navigationOptions, 'tag', 'div');
		return Html::tag($tag, $content, $navigationOptions);
	}

	public function renderNavigationSection($name)
	{
		$navigationOptions = $this->navigationOptions;
		switch ($name) {
			case '{summary}':
				ArrayHelper::remove($navigationOptions['summary'], 'id');
				$tag = ArrayHelper::remove($navigationOptions['summary'], 'tag', 'span');
				return Html::tag($tag, Html::tag('span', 0, ['id'=>'page_num']).' / '.Html::tag('span', 0, ['id'=>'page_count']), $navigationOptions['summary']);
			case '{prev}':
				ArrayHelper::remove($navigationOptions['prev'], 'id');
				$tag = ArrayHelper::remove($navigationOptions['prev'], 'tag', 'button');
				return Html::tag($tag, Yii::t('app', 'Previous'), ArrayHelper::merge($navigationOptions['prev'], ['id'=>'prev']));
			case '{next}':
				ArrayHelper::remove($navigationOptions['next'], 'id');
				$tag = ArrayHelper::remove($navigationOptions['next'], 'tag', 'button');
				return Html::tag($tag, Yii::t('app', 'Next'), ArrayHelper::merge($navigationOptions['next'], ['id'=>'next']));
			case '{zoomIn}':
				ArrayHelper::remove($navigationOptions['zoomIn'], 'id');
				$tag = ArrayHelper::remove($navigationOptions['zoomIn'], 'tag', 'button');
				return Html::tag($tag, Yii::t('app', 'Zoom (+)'), ArrayHelper::merge($navigationOptions['zoomIn'], ['id'=>'zoomIn']));
			case '{zoomOut}':
				ArrayHelper::remove($navigationOptions['zoomOut'], 'id');
				$tag = ArrayHelper::remove($navigationOptions['zoomOut'], 'tag', 'button');
				return Html::tag($tag, Yii::t('app', 'Zoom (-)'), ArrayHelper::merge($navigationOptions['zoomOut'], ['id'=>'zoomOut']));
			default:
				return false;
		}
	}

	public function renderPreview()
	{
		$previewOptions = $this->previewOptions;
		$tag = ArrayHelper::remove($previewOptions, 'tag', 'canvas');

		return Html::tag($tag, '', $previewOptions);
	}
}