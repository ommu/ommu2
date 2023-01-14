<?php
/**
 * AssetController
 * @var $this app\components\View
 *
 * Reference start
 * TOC :
 *  Index
 *  Clear
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 13 January 2023, 22:40 WIB
 * @link https://www.ommu.id
 *
 */

namespace app\modules\admin\controllers;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\helpers\ArrayHelper;
use Symfony\Component\Filesystem\Exception\IOException;

class AssetController extends Controller
{
    use \ommu\traits\FileTrait;

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
        throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'The requested page does not exist.'));
	}

	/**
	 * Clear Action
	 */
	public function actionClear()
	{
        if (Yii::$app->request->isPost) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            try {
                $result = $this->clearAssets();
                $result[] = (!empty($result)? "\n" : '').Yii::t('app', 'Assets sukses dibersihkan...');
                Yii::$app->broadcaster->publish('devtool', ['message' => join("\n", $result)]);
            } catch (IOException $e) {
                $result[] =Yii::t('app', 'Assets gagal dibersihkan...\n');
                $result[] = $e->getMessage();
                Yii::$app->broadcaster->publish('devtool', ['message' => join("\n", $result)]);
            }
        }

		$this->view->title = Yii::t('app', 'Clear Asset Files');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->renderModal('admin_clear');
	}

	/**
	 * {@inheritdoc}
	 */
    private function clearAssets()
    {
        $assetsPath = Yii::getAlias('@webroot/assets');
        $iter = new \DirectoryIterator($assetsPath);

        $result = [];
        foreach ($iter as $fileInfo) {
            if (!file_exists($fileInfo->getPathname()) || $fileInfo->isDot() || $fileInfo->isFile()) {
                continue;
            }
        
            try {
                $result[] = Yii::t('app', '{path} deleted...', ['path' => $fileInfo->getPathname()]);
                self::fs()->remove($fileInfo->getPathname());
            } catch (\Exception $e) {
            }
        }

        return $result;
    }
}
