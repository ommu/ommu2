<?php
/**
 * LoginController
 * @var $this yii\web\View
 *
 * Reference start
 * TOC :
 *  Index
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 3 January 2018, 14:02 WIB
 * @link https://github.com/ommu/ommu
 *
 */
 
namespace app\modules\admin\controllers;

use Yii;
use yii\helpers\Url;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use app\modules\user\models\LoginForm;
use yii\validators\EmailValidator;

class LoginController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init() 
	{
		parent::init();

		$this->layout ='login';
	}

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
		return ['index'];
	}

	/**
	 * {@inheritdoc}
	 */
	public function actions() {
		$actions = parent::actions();
		if (Yii::$app->request->getContentType() == 'application/json' || Yii::$app->request->isAjax) {

			$this->enableCsrfValidation = false;
			$actions['index'] = [
				'class' => 'ommu\users\actions\login\ActionIndex',
			];
		}
		return $actions;
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionIndex()
	{
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
        }

		if (Yii::$app->isSocialMedia()) {
			return $this->redirect(Url::to(['/site/login']));
        }

		$model = new LoginForm();
		if (Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			$model->isAdmin = true;

			$validator = new EmailValidator();
			if ($validator->validate($model->username) === true) {
				$model->setByEmail(true);
            }

			if ($model->login()) {
				return $this->goBack();
            }
		}

		$this->view->title = Yii::t('app', 'Login');
		$this->view->description = Yii::t('app', 'Login to access your {app-name} Account', ['app-name' => Yii::$app->name]);
		$this->view->keywords = '';
		return $this->render('admin_index', [
			'model' => $model,
		]);
	}
}
