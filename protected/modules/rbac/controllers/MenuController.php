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
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 2 January 2018, 16:17 WIB
 * @link https://github.com/ommu/ommu
 *
 */
 
namespace app\modules\rbac\controllers;

use Yii;
use app\components\Controller;
use yii\filters\VerbFilter;
use app\models\Menu;
use app\models\search\Menu as MenuSearch;
use mdm\admin\components\Helper;

class MenuController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		if(!in_array(Yii::$app->user->identity->level_id, [1,2]))
			throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'You are not allowed to perform this action.'));

		parent::init();
	}

	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
		];
	}

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

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());

			if($model->save()) {
				Helper::invalidate();
				if(($app = Yii::$app->request->get('app')) != null) {
					if(!Yii::$app->request->isAjax)
						return $this->redirect(['view', 'id'=>$model->id, 'app'=>$app]);
					return $this->redirect(Yii::$app->request->referrer ?: ['index', 'app'=>$app]);
				}

				if(!Yii::$app->request->isAjax)
					return $this->redirect(['view', 'id'=>$model->id]);
				return $this->redirect(Yii::$app->request->referrer ?: ['index']);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
			}
		}

		$this->view->title = Yii::t('app', 'Create Menu');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('create', [
			'model' => $model,
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		if ($model->menuParent) {
			$model->parent_name = $model->menuParent->name;
		}

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());

			if($model->save()) {
				Helper::invalidate();
				if(($app = Yii::$app->request->get('app')) != null) {
					if(!Yii::$app->request->isAjax)
						return $this->redirect(['view', 'id'=>$model->id, 'app'=>$app]);
					return $this->redirect(Yii::$app->request->referrer ?: ['index', 'app'=>$app]);
				}

				if(!Yii::$app->request->isAjax)
					return $this->redirect(['view', 'id'=>$model->id]);
				return $this->redirect(Yii::$app->request->referrer ?: ['index']);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
			}
		}

		$this->view->title = Yii::t('app', 'Update Menu: {menu}', ['menu'=>$model->name]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('update', [
			'model' => $model,
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'Detail Menu: {menu}', ['menu'=>$model->name]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('view', [
			'model' => $model,
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		if($model->delete())
			Helper::invalidate();

		if(($app = Yii::$app->request->get('app')) != null) {
			if(!Yii::$app->request->isAjax)
				return $this->redirect(['index', 'app'=>$app]);
			return $this->redirect(Yii::$app->request->referrer ?: ['index', 'app'=>$app]);
		}

		if(!Yii::$app->request->isAjax)
			return $this->redirect(['index']);
		return $this->redirect(Yii::$app->request->referrer ?: ['index']);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function findModel($id)
	{
		if(($model = Menu::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
