<?php
namespace app\controllers;

use Yii;
use yii\helpers\Url;
use app\components\Controller;
use yii\web\Response;
use app\modules\user\models\LoginForm;
use yii\validators\EmailValidator;
use yii\web\NotFoundHttpException;

class SiteController extends Controller
{
	public static $backoffice = false;
	public $isLoginLayout = false;

	/**
	 * {@inheritdoc}
	 */
	public function actions()
	{
		return [
			'error' => [
				'class' => 'app\components\actions\ErrorAction',
				'layout' => 'error',
				'view' => 'front_error',
			],
			'contact' => [
				'class' => 'app\components\actions\ContactAction',
				'view' => 'front_contact',
			],
			'captcha' => [
				'class' => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
			],
		];
	}

	/**
	 * Displays homepage.
	 *
	 * @return string
	 */
	public function actionIndex()
	{
		if(Yii::$app->id == 'back3nd') {
			if(Yii::$app->user->isGuest)
				return $this->redirect(Url::to(['/admin/login']));
			else
				return $this->redirect(Url::to(['/admin/dashboard/index']));
		}

		if(Yii::$app->isMaintenance()) {
			$maintenance_theme = Yii::$app->setting->get(join('_', [Yii::$app->id, 'maintenance_theme']), 'arnica');
			$this->view->theme($maintenance_theme);
		}

		$this->view->title = Yii::t('app', 'Home');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('front_index');
	}

	/**
	 * Login action.
	 *
	 * @return Response|string
	 */
	public function actionLogin()
	{
		if(!Yii::$app->isSocialMedia())
			throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));

		if(!Yii::$app->user->isGuest)
			return $this->goHome();

		$model = new LoginForm();
		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());

			$validator = new EmailValidator();
			if($validator->validate($model->username) === true)
				$model->setByEmail(true);

			if($model->login())
				return $this->goBack();
		}

		$this->isLoginLayout = true;
		$this->view->descriptionShow = true;
		$this->view->title = Yii::t('app', 'Welcome back!');
		$this->view->description = Yii::t('app', 'Login to access your {app-name} Account', ['app-name'=>Yii::$app->name]);
		$this->view->keywords = '';
		return $this->render('front_login', [
			'model' => $model,
		]);
	}

	/**
	 * Logout action.
	 *
	 * @return Response
	 */
	public function actionLogout()
	{
		Yii::$app->user->logout();

		return $this->goHome();
	}

	/**
	 * Displays about page.
	 *
	 * @return string
	 */
	public function actionAbout()
	{
		$this->view->title = Yii::t('app', 'Abouts');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('about');
	}

	/**
	 * Displays about static page.
	 *
	 * @return string
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);
		\ommu\core\models\CorePageViews::insertView($model->page_id);

		$this->view->title = $model->title->message;
		$this->view->description = $model->description->message;
		$this->view->keywords = '';
		return $this->render('front_view', [
			'model' => $model,
		]);
	}

	/**
	 * Finds the CorePages model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return CorePages the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = \ommu\core\models\CorePages::findOne($id)) !== null)
			return $model;

		throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
