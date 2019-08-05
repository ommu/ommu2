<?php
/**
 * MenuController
 * @var $this yii\web\View
 * @var $model app\models\Menu
 *
 * MenuController implements the CRUD actions for Menu model.
 * Reference start
 * TOC :
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 2 January 2018, 16:17 WIB
 * @link https://github.com/ommu/ommu
 *
 */
 
namespace app\modules\rbac\controllers;

use Yii;
use app\models\Menu;
use app\models\search\Menu as MenuSearch;

class MenuController extends \mdm\admin\controllers\MenuController
{
	/**
	 * {@inheritdoc}
	 */
	public function actionIndex()
	{
		$searchModel = new MenuSearch;
		$dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

		return $this->render('index', [
			'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionCreate()
	{
		$model = new Menu;

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			\mdm\admin\components\Helper::invalidate();
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	protected function findModel($id)
	{
		if (($model = Menu::findOne($id)) !== null) {
			return $model;
		} else {
			throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
		}
	}
}
