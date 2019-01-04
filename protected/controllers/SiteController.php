<?php
namespace app\controllers;

use Yii;
use app\components\Controller;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\helpers\Url;
use app\modules\user\models\LoginForm;
use yii\validators\EmailValidator;
use app\models\ContactForm;

class SiteController extends Controller
{
	public static $backoffice = false;

	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only' => ['logout'],
				'rules' => [
					[
						'actions' => ['logout'],
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
				'view' => 'front_error',
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
		return $this->render('index');
	}

	/**
	 * Login action.
	 *
	 * @return Response|string
	 */
	public function actionLogin()
	{
		if(!Yii::$app->user->isGuest)
			return $this->goHome();

		if(!Yii::$app->isSocialMedia())
			return $this->redirect(Url::to(['/admin/login']));

		$model = new LoginForm();
		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());

			$validator = new EmailValidator();
			if($validator->validate($model->username) === true)
				$model->setByEmail(true);

			if($model->login())
				return $this->goBack();
		}

		$this->view->title = Yii::t('app', 'Login');
		$this->view->description = '';
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
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
