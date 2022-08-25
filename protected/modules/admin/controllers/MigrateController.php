<?php
/**
 * MigrateController
 * @var $this app\components\View
 *
 * Reference start
 * TOC :
 *  Index
 *  Set
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 25 August 2022, 13:23 WIB
 * @link https://www.ommu.id
 *
 */

namespace app\modules\admin\controllers;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;

class MigrateController extends Controller
{
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
	 * Set Action
	 */
	public function actionSet()
	{
        if (Yii::$app->request->isPost) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $postData = Yii::$app->request->post();

            $migrationPath = $postData['modulePath'];
            $buffer = \app\commands\MigrateController::webMigrateUp($migrationPath);
            Yii::$app->broadcaster->publish("devtool", ['message' => $buffer]);
        }

        $modules = [];
        foreach(Yii::$app->params['moduleAutoloadPaths'] as $modulePath) {
            $modulePath = Yii::getAlias($modulePath);
            foreach(scandir($modulePath) as $moduleId) {
                if ($moduleId == '.' || 
                    $moduleId == '..' ||
                    is_file($modulePath . DIRECTORY_SEPARATOR . $moduleId) ||
                    in_array($moduleId, Yii::$app->params['dontLoadModule'])) {
                        continue;
                }

                $moduleDir = $modulePath . DIRECTORY_SEPARATOR . $moduleId;
                if (is_dir($moduleDir) && is_dir($moduleDir . DIRECTORY_SEPARATOR . 'migrations')) {
                    try {
                        $moduleDir = $moduleDir . DIRECTORY_SEPARATOR . 'migrations';
                        $modules[$moduleDir] = $moduleId;
                    } catch(\Exception $ex) {
                    }
                }
            }
        }

		$this->view->title = Yii::t('app', 'Migrate');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->renderModal('admin_set', [
            'modules' => $modules,
        ]);
	}

}
