<?php
/**
 * ComposerController
 * @var $this app\components\View
 *
 * Reference start
 * TOC :
 *  Index
 *  Set
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 25 August 2022, 09:14 WIB
 * @link https://www.ommu.id
 *
 */

namespace app\modules\admin\controllers;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;

class ComposerController extends Controller
{
    const COMPOSER_CMD = '/usr/local/bin/composer';

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

            $model = new \app\components\jobs\ComposerJob([
                'user_id' => Yii::$app->user->id,
                'cmd' => self::COMPOSER_CMD,
                'args' => $postData['args'],
            ]);
            $model->execute();
        }

		$this->view->title = Yii::t('app', 'Composer');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->renderModal('admin_set');
	}

}
