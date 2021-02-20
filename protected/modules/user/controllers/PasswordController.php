<?php
/**
 * PasswordController
 * @var $this app\modules\user\controllers\PasswordController
 * @var $model ommu\users\models\UserForgot
 *
 * Reference start
 * TOC :
 *	Index
 *	Change
 *	Forgot
 *	Reset
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 27 November 2018, 09:40 WIB
 * @link https://github.com/ommu/ommu
 *
 */

namespace app\modules\user\controllers;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use ommu\users\models\UserForgot;
use app\modules\user\models\Users;
use yii\helpers\Url;
use yii\helpers\Json;
use app\components\widgets\ActiveForm;

class PasswordController extends Controller
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
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function allowAction(): array {
		return ['index', 'forgot', 'reset'];
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionIndex()
	{
		return $this->redirect(['change']);
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionChange()
	{
		$model = $this->findModel();
		$model->scenario = Users::SCENARIO_CHANGE_PASSWORD;

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			$model->isForm = true;

			if($model->save()) {
				Yii::$app->user->logout();

				if(!Yii::$app->request->isAjax) {
					Yii::$app->session->setFlash('success', Yii::t('app', 'Password changed.'));
					if(Yii::$app->id == 'back3nd')
						return $this->redirect(['/admin/login']);
					return $this->redirect(['/site/login']);
				}

			} else {
				if(Yii::$app->request->isAjax)
					return Json::encode(ActiveForm::validate($model));
			}
		}

		$this->view->descriptionShow = true;
		$this->view->title = !Yii::$app->request->get() ? 
			Yii::t('app', 'Change Password') : 
			Yii::t('app', 'Change Password Success');
		$this->view->description = Yii::t('app', 'It\'s a good idea to use a strong password that you\'re not using elsewhere');
		$this->view->keywords = '';
		return $this->render('front_change', [
			'model' => $model,
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionForgot()
	{
		$model = new UserForgot();
		$model->scenario = UserForgot::SCENARIO_WITH_FORM;
		$model->setAttributeLabels(['email_i'=>Yii::t('app', 'Username or Email')]);

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			if($model->save()) {
				if(!Yii::$app->request->isAjax) {
					Yii::$app->session->setFlash('success', Yii::t('app', 'Hi, <strong>{displayname}</strong> an email with instructions for creating a new password has been sent to <strong>{email}</strong>', [
						'displayname' => $model->user->displayname,
						'email' => $model->user->email,
					]));
					return $this->redirect(['forgot']);
				}

			} else {
				if(Yii::$app->request->isAjax)
					return Json::encode(ActiveForm::validate($model));
			}
		}

		$this->view->descriptionShow = true;
		$this->view->title = Yii::t('app', 'Trouble Logging In?');
		$this->view->description = Yii::t('app', 'Please enter your <strong>username</strong> or <strong>email</strong> and we\'ll send you instructions on how to reset your password');
		$this->view->keywords = '';
		return $this->oRender('front_forgot', [
			'model' => $model,
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionReset()
	{
		if(($code = Yii::$app->request->get('cd')) == null)
			return $this->goHome();

		$msg = Yii::$app->request->get('msg');
		$forgot = UserForgot::findOne(['code' => $code]);

		if(!$msg && $forgot == null) {
			$render = 'novalid';
			Yii::$app->session->setFlash('error', Yii::t('app', 'Reset password code tidak ditemukan.'));
			if(!$code)
				return $this->redirect(['reset', 'cd' => $code]);
		}

		if(!$msg && $forgot->expired == 1) {
			$render = 'expired';
			Yii::$app->session->setFlash('error', Yii::t('app', 'Reset password code tidak dapat digunakan.'));
			if(!$code)
				return $this->redirect(['reset', 'cd' => $code]);
		}

		if($render != 'novalid' && $render != 'expired') {
			$render = 'valid';

			$model = $this->findModel($forgot->user_id);
			$model->scenario = Users::SCENARIO_RESET_PASSWORD;
	
			if(Yii::$app->request->isPost) {
				$model->load(Yii::$app->request->post());
				$model->isForm = true;
	
				if($model->save()) {
					$forgot->publish = 0;
					$forgot->save();
	
					Yii::$app->user->logout();
	
					if(!Yii::$app->request->isAjax) {
						Yii::$app->session->setFlash('success', Yii::t('app', 'You have successfully changed your password. To sign in to your account, use your username or email and new password.'));
						return $this->redirect(['reset', 'cd' => $code, 'msg' => 'success']);
					}
	
				} else {
					if(Yii::$app->request->isAjax)
						return Json::encode(ActiveForm::validate($model));
				}
			}
		}

		$this->view->descriptionShow = true;
		$this->view->title = !$msg ? 
			Yii::t('app', 'Reset Password?') : 
			Yii::t('app', 'Reset Password Success');
		$this->view->description = Yii::t('app', 'It\'s a good idea to use a strong password that you\'re not using elsewhere');
		$this->view->keywords = '';
		return $this->render('front_reset', [
			'model' => $model,
			'render' => $render,
			'msg' => $msg,
		]);
	}

	/**
	 * Finds the Users model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Users the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id=null)
	{
		if($id == null)
			$id = Yii::$app->user->id;
		if(($model = Users::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
