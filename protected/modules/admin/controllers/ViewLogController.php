<?php
/**
 * ViewLogController
 * @var $this app\components\View
 *
 * Reference start
 * TOC :
 *  Download
 *  Index
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 13 January 2023, 19:34 WIB
 * @link https://www.ommu.id
 *
 */

namespace app\modules\admin\controllers;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\helpers\Html;

class ViewLogController extends Controller
{
    private static $_maxLineSet = false;

	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function allowAction(): array {
		return [];
	}

	/**
	 * Index Action
	 */
	public function actionIndex()
	{
        $maxLineFile = 0;
        $page = Yii::$app->request->get('page', 1);
        $perPage = 500;

        $fname = Yii::$app->getRuntimePath() . '/logs/app.log';
        if (self::$_maxLineSet == false) {
            self::$_maxLineSet = true;
            $maxLineFile = $this->getMaxLineFile($fname);
        }

        $totalPage = ceil($maxLineFile/$perPage);
        $logs = $this->readLastLine($fname, ($page * $perPage));

		$this->layout = 'default';
		$this->view->title = Yii::t('app', 'View Logs');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_index', [
            'logs' => $logs,
            'logFiles' => $this->getDownloadLogUrl(),
            'totalPage' => $totalPage,
            'maxLines' => $maxLineFile,
            'perPage' => $perPage,
        ]);
	}

	/**
	 * Download Action
	 */
	public function actionDownload()
	{
        $name = Yii::$app->request->getQueryParam('fileName');
        $fileName = 'app.log';
        if ($name != null) {
            $fileName = $name;
        }

        $zip = new \ZipArchive();
        $zipName = join('/', [sys_get_temp_dir(), $fileName]) . '.zip';
        if (!$zip->open($zipName, \ZIPARCHIVE::CREATE)) {
            throw new \Exception('Tidak bisa membuka file zip');
        }
        $zip->addFile(join('/', [Yii::$app->getRuntimePath(), 'logs', $fileName]), $fileName);
        $zip->close();

        Yii::$app->response->sendFile($zipName, null);
	}

	/**
	 * {@inheritdoc}
	 */
    private function getMaxLineFile(string $fileName)
    {
        $fp = fopen($fileName, 'r');
        $lines = [];
        while (!feof($fp)) {
            $lines[] = fgets($fp);
        }
        fclose($fp);
        return count($lines);
    }

	/**
	 * {@inheritdoc}
	 */
    private function readLastLine($fname, $countLine=500) 
    {
        $maxLineLength = 300;

        $fp = fopen($fname, 'r');
        $data = fseek($fp, -($countLine * $maxLineLength), SEEK_END);
        $lines = [];
        while(!feof($fp)) {
            $lines[] = fgets($fp);
        }

        $c = count($lines);
        $i = $c >= $countLine? $c - $countLine: 0;

        ob_start();
        ob_implicit_flush(false);
        for(; $i < $c; ++$i) {
            echo $lines[$i];
        }

        return ob_get_clean();
    }

	/**
	 * {@inheritdoc}
	 */
    private function getLogFiles(): array
    {
        $filePath = Yii::$app->getRuntimePath() . '/logs';
        $result = [];
        foreach(new \DirectoryIterator($filePath) as $fileInfo) {
            if($fileInfo->isDot()) continue;
            $result[] = $fileInfo->getFilename();
        }
        return $result;
    }

	/**
	 * {@inheritdoc}
	 */
    private function getDownloadLogUrl(): array
    {
        $logFiles = $this->getLogFiles();

		$items = [];
		foreach ($logFiles as $file) {
            $items[$file] = Html::a($file, ['download', 'fileName' => $file], ['title' => Yii::t('app', 'Download {file}', ['file' => $file])]);
        }

		return $items;
    }
}
