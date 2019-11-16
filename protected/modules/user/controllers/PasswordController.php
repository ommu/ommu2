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
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
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

class PasswordController extends Controller
{
	public static $backoffice = false;

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
					return Json::encode(\app\components\ActiveForm::validate($model));
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
					return Json::encode(\app\components\ActiveForm::validate($model));
			}
		}

		$this->view->descriptionShow = true;
		$this->view->title = Yii::t('app', 'Forgot Password?');
		$this->view->description = Yii::t('app', 'Please enter your <strong>email</strong> address and we\'ll send you instructions on how to reset your password');
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
		if(!Yii::$app->request->get())
			return $this->goHome();

		$code = Yii::$app->request->get('cd');
		$forgot = UserForgot::findOne(['code'=>$code]);

		if($forgot != null) {
			if($forgot->view->expired == 1)
				$render = 'expired';
			else {
				$render = 'valid';
				$ecc3Condition = Yii::$app->params['ecc3']['change_password']['form'];
				if($ecc3Condition == true) {
					$model = \app\modules\ecc\models\Users::findOne(['id'=>$forgot->user->_id, 'email'=>$forgot->user->email]);
					$model->scenario = \app\modules\ecc\models\Users::SCENARIO_RESET_PASSWORD;
				} else {
					$model = $this->findModel($forgot->user_id);
					$model->scenario = Users::SCENARIO_RESET_PASSWORD;
				}

				if(Yii::$app->request->isPost) {
					$model->load(Yii::$app->request->post());
					$model->isForm = true;
		
					if($model->save()) {
						//$forgot->publish = 0;
						$forgot->save();

						//Yii::$app->user->logout();

						if(!Yii::$app->request->isAjax) {
							Yii::$app->session->setFlash('success', Yii::t('app', 'Password success updated.'));
							return $this->redirect(['reset', 'message'=>'success']);
						} else
							return Json::encode(['successUrl'=>Url::to(['reset', 'message'=>'success'])]);
		
					} else {
						if(Yii::$app->request->isAjax)
							return Json::encode(\app\components\ActiveForm::validate($model));
					}
				}
			}
		} else 
			$render = 'novalid';

		if($render == 'novalid')
			$description = Yii::t('app', 'Reset password code tidak ditemukan.');
		else if($render == 'expired')
			$description = Yii::t('app', 'Reset password code tidak dapat digunakan.');
		else if($render == 'valid')
			$description = Yii::t('app', 'Masukkan password baru anda pada inputan berikut.');

		$this->view->title = !Yii::$app->request->get('message') ? 
			Yii::t('app', 'Reset Password') : 
			Yii::t('app', 'Reset Password Success');
		$this->view->description = !Yii::$app->request->get('message') ? 
			$description : 
			Yii::t('app', 'You have successfully changed your password. To sign in to your account, use your email and new password at the following link:');
		$this->view->keywords = '';
		return $this->render('front_reset', [
			'render' => $render,
			'model' => $model,
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
