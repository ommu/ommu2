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
use mdm\admin\controllers\MenuController as MdmMenuController;

class MenuController extends MdmMenuController
{
	/**
	 * Creates a new Menu model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
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
	 * Finds the Menu model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param  integer $id
	 * @return Menu the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
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
