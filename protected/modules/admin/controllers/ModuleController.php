<?php
/**
 * ModuleController
 * @var $this yii\web\View
 * @var $model app\modules\admin\models\Modules
 *
 * ModuleController implements the CRUD actions for Modules model.
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	View
 *	Delete
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 26 December 2017, 09:41 WIB
 * @link https://github.com/ommu/ommu
 *
 */
 
namespace app\modules\admin\controllers;

use Yii;
use yii\filters\VerbFilter;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use app\modules\admin\models\Modules;
use app\modules\admin\models\search\Modules as ModulesSearch;
use yii\web\HttpException;

class ModuleController extends Controller
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
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
					'enabled' => ['POST'],
				],
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionIndex()
	{
		Yii::$app->moduleManager->flushCache();
		Yii::$app->moduleManager->setModules();
		return $this->redirect(['manage']);
	}

	/**
	 * Lists all Modules models.
	 * @return mixed
	 */
	public function actionManage()
	{
		Yii::$app->moduleManager->getModules(['includeCoreModules' => true]);

		$searchModel = new ModulesSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		$gridColumn = Yii::$app->request->get('GridColumn', null);
		$cols = [];
		if($gridColumn != null && count($gridColumn) > 0) {
			foreach($gridColumn as $key => $val) {
				if($gridColumn[$key] == 1)
					$cols[] = $key;
			}
		}
		$columns = $searchModel->getGridColumn($cols);

		$this->view->title = Yii::t('app', 'Modules');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
	}

	/**
	 * Displays a single Modules model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'Detail {model-class}: {module-id}', ['model-class' => 'Module', 'module-id' => $model->module_id]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing Modules model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);

		if(!(Yii::$app->moduleManager->hasModule($model->module_id) && Yii::$app->moduleManager->canRemoveModule($model->module_id))) {
			Yii::$app->session->setFlash('error', Yii::t('app', '{module-id} cannot be deleted.', array(
				'module-id'=>ucfirst($model->module_id),
			)));
			return $this->redirect(['manage']);
		}

		$module = Yii::$app->moduleManager->getModule($model->module_id);
		if($module == null) {
			// throw new HttpException(500, Yii::t('app', 'Could not find request module!'));
			Yii::$app->session->setFlash('error', Yii::t('app', 'Could not find request module!'));
			return $this->redirect(['manage']);
		}

		if(!is_writable($module->getBasePath())) {
			// throw new HttpException(500, Yii::t('app', 'Module path {module-path} is not writeable!', array(
			// 	'module-path'=>$module->getPath(),
			// )));
			Yii::$app->session->setFlash('error', Yii::t('app', 'Module path {module-path} is not writeable!', array(
				'module-path'=>$module->getPath(),
			)));
			return $this->redirect(['manage']);
		}

		if($model->delete()) {
			$module->uninstall();

			Yii::$app->session->setFlash('success', Yii::t('app', '{module-id} module success deleted.', array('module-id'=>ucfirst($model->module_id))));
			return $this->redirect(['index']);
		}
	}

	/**
	 * actionEnabled an existing Modules model.
	 * If installed is successful, the browser will be redirected to the 'manage' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionEnabled($id)
	{
		$model = $this->findModel($id);
		$replace = $model->enabled == 1 ? 0 : 1;
		$model->enabled = $replace;

		$module = Yii::$app->moduleManager->getModule($model->module_id);
		if($module == null) {
			// throw new HttpException(500, Yii::t('app', 'Could not find request module!'));
			Yii::$app->session->setFlash('error', Yii::t('app', 'Could not find request module!'));
			return $this->redirect(['manage']);
		}

		if($replace == 0) {
			$disable = $module->disable();
			if(is_object($disable))
				Yii::$app->session->setFlash('success', Yii::t('app', '{module-id} module success disabled.', array('module-id'=>ucfirst($model->module_id))));
			else
				Yii::$app->session->setFlash('error', $disable);
		} else {
			$enable = $module->enable();
			if(is_object($enable))
				Yii::$app->session->setFlash('success', Yii::t('app', '{module-id} module success enabled.', array('module-id'=>ucfirst($model->module_id))));
			else
				Yii::$app->session->setFlash('error', $enable);
		}

		return $this->redirect(['index']);
	}

	/**
	 * Finds the Modules model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Modules the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = Modules::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
