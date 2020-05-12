<?php
/**
 * VerifyController
 * @var $this app\modules\user\controllers\VerifyController
 * @var $model ommu\users\models\UserVerify
 *
 * Reference start
 * TOC :
 *	Index
 *	Email
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 3 December 2018, 06:19 WIB
 * @link https://github.com/ommu/ommu
 *
 */

namespace app\modules\user\controllers;

use Yii;
use app\components\Controller;
use ommu\users\models\UserVerify;
use app\modules\user\models\Users;
use yii\helpers\Url;
use yii\helpers\Json;
use app\components\widgets\ActiveForm;

class VerifyController extends Controller
{
	public static $backoffice = false;

	/**
	 * {@inheritdoc}
	 */
	public function actionIndex()
	{
		$model = new UserVerify();
		$model->scenario = UserVerify::SCENARIO_WITH_FORM;

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());

			if($model->save()) {
				if(!Yii::$app->request->isAjax) {
					Yii::$app->session->setFlash('success', Yii::t('app', 'Hi, <strong>{displayname}</strong> an email with verification code has been sent to <strong>{email}</strong>', [
						'displayname' => $model->user->displayname,
						'email' => $model->user->email,
					]));
					return $this->redirect(['index']);
				}

			} else {
				if(Yii::$app->request->isAjax)
					return Json::encode(ActiveForm::validate($model));
			}
		}

		$this->view->descriptionShow = true;
		$this->view->title = Yii::t('app', 'Verify Email?');
		$this->view->description = Yii::t('app', 'Please verify your email address to finish setting up your account, we just need to make sure this email address is yours');
		$this->view->keywords = '';
		return $this->oRender('front_index', [
			'model' => $model,
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionEmail()
	{
		if(($code = Yii::$app->request->get('cd')) == null)
			return $this->goHome();

		$msg = Yii::$app->request->get('msg');
		$verify = UserVerify::findOne(['code'=>$code]);

		if(!$msg && $verify == null) {
			$render = 'novalid';
			Yii::$app->session->setFlash('error', Yii::t('app', 'Verification code not found.'));
			if(!$code)
				return $this->redirect(['email', 'cd'=>$code]);
		}

		if(!$msg && $verify->expired == 1) {
			$render = 'expired';
			Yii::$app->session->setFlash('error', Yii::t('app', 'Verification code expired.'));
			if(!$code)
				return $this->redirect(['email', 'cd'=>$code]);
		}

		if($render != 'novalid' && $render != 'expired') {
			$render = 'valid';

			$model = $this->findModel($verify->user_id);
			$model->verified = 1;

			if($model->save(false, ['verified', 'update_date', 'update_ip'])) {
				$verify->publish = 0;
				$verify->save();

				if(!Yii::$app->request->isAjax) {
					Yii::$app->session->setFlash('success', Yii::t('app', 'Your email has been successfully verified'));
					if(!$msg)
						return $this->redirect(['email', 'cd'=>$code, 'msg'=>'success']);
				}

			} else {
				if(Yii::$app->request->isAjax)
					return Json::encode(ActiveForm::validate($model));
			}
		}

		$this->view->descriptionShow = true;
		$this->view->title = !$msg ? 
			Yii::t('app', 'Verify Email?') :
			Yii::t('app', 'Verify Email Success');
		$this->view->description = Yii::t('app', 'Please verify your email address to finish setting up your account, we just need to make sure this email address is yours');
		$this->view->keywords = '';
		return $this->render('front_email', [
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
	protected function findModel($id)
	{
		if(($model = Users::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
