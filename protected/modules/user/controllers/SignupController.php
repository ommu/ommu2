<?php
/**
 * SignupController
 * @var $this app\modules\user\controllers\SignupController
 * @var $model ommu\users\models\Users
 *
 * Reference start
 * TOC :
 *  Index
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 19 November 2018, 06:26 WIB
 * @link https://github.com/ommu/ommu
 *
 */

namespace app\modules\user\controllers;

use Yii;
use app\components\Controller;
use app\models\CoreSettings;
use app\modules\user\models\Users;
use yii\helpers\Json;
use app\components\ActiveForm;

class SignupController extends Controller
{
	public static $backoffice = false;
	public $isLoginLayout = false;

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		parent::init();

		if(!Yii::$app->isSocialMedia())
			throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));

		if(!Yii::$app->user->isGuest)
			return $this->goHome();
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionIndex()
	{
		$setting = CoreSettings::find()
			->select(['signup_username', 'signup_random', 'signup_inviteonly', 'signup_checkemail'])
			->where(['id' => 1])
			->one();

		$model = new Users();
		$model->scenario = Users::SCENARIO_REGISTER;
		if (Yii::$app->isSocialMedia() && $setting->signup_inviteonly != 0 && $setting->signup_checkemail == 1)
			$model->scenario = Users::SCENARIO_REGISTER_WITH_INVITE_CODE;

		if (Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());

			if ($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Thank you for join us as a member, please login and follow some instruction to complate your registration'));
				return $this->redirect(['success']);

			} else {
				if (Yii::$app->request->isAjax)
					return Json::encode(ActiveForm::validate($model));
			}
		}

		$this->isLoginLayout = true;
		$this->view->descriptionShow = true;
		$this->view->title = Yii::t('app', 'Sign up!');
		$this->view->description = Yii::t('app', 'Create an account on {app-name}', ['app-name'=>Yii::$app->name]);
		$this->view->keywords = '';
		return $this->oRender('front_index', [
			'model' => $model,
			'setting' => $setting,
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionSuccess()
	{
		$this->isLoginLayout = true;
		$this->view->descriptionShow = true;
		$this->view->title = Yii::t('app', 'Signup Success');
		$this->view->description = Yii::t('app', 'Create an account on {app-name}', ['app-name'=>Yii::$app->name]);
		$this->view->keywords = '';
		return $this->render('front_success');
	}
}
