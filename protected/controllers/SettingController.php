<?php
/**
 * SettingController
 * @var $this app\components\View
 *
 * Reference start
 * TOC :
 *	Update
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 22 April 2019, 23:47 WIB
 * @link https://github.com/ommu/ommu
 *
 */

namespace app\controllers;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use app\models\BaseSetting;
use app\components\Application;
use app\models\Theme;

class SettingController extends Controller
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
		];
	}

	/**
	 * Update Action
	 */
	public function actionUpdate()
	{
		$app = Yii::$app->request->get('app');
		$appName = Application::getAppId();

		$model = new BaseSetting(['app'=>$app ? $app : $appName]);

		$themes = [];
		foreach($allTheme = Theme::getThemes() as $key => $val) {
			$themes[$key] = $val ? $val['name'] : ucwords($key);
		}

		$backSubLayout = $allTheme[$model->backoffice_theme]['sublayout'];
		if(!isset($backSubLayout))
			$backSubLayout = [];
		$frontSubLayout = $allTheme[$model->theme]['sublayout'];
		if(!isset($frontSubLayout))
			$frontSubLayout = [];

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			if($model->save()) {
				$name = unserialize($model->name);
				$success = Yii::t('app', 'General setting <strong>{app-name}</strong> success updated.', ['app-name'=>$model->name['small']]);
				if($app != null)
					$success = Yii::t('app', 'App setting <strong>{app-name}</strong> success updated.', ['app-name'=>$model->name['long']]);
				Yii::$app->session->setFlash('success', $success);
				if($app != null)
					return $this->redirect(['update', 'app'=>$app]);
				return $this->redirect(['update']);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\ActiveForm::validate($model));
			}
		}

		$title = Yii::t('app', 'General Settings');
		$description = Yii::t('app', 'This page contains general settings that affect your entire {app-name} application.', ['app-name'=>$model->name['small']]);
		if($app != null) {
			$title = Yii::t('app', 'App Setting: {app-name}', ['app-name'=>$model->name['small']]);
			$description = Yii::t('app', 'This page contains app settings that affect your entire {app-name} application.', ['app-name'=>$model->name['long']]);
		}

		$this->view->title = $title;
		$this->view->description = $description;
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
			'themes' => $themes,
			'backSubLayout' => $backSubLayout,
			'frontSubLayout' => $frontSubLayout,
		]);
	}

	/**
	 * ThemeSublayout Action
	 */
	public function actionSublayout($theme)
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$yaml = Theme::themeParseYaml($theme);

		return is_array($yaml) ? ($yaml['sublayout'] ? $this->getSublayout($yaml['sublayout']) : []) : [];
	}

	/**
	 * GetSublayout
	 */
	public function getSublayout($sublayout)
	{
		$result = [];
		foreach($sublayout as $key => $val) {
			$result[] = [
				'id' => $key, 
				'label' => $val,
			];
		}
		return $result;
	}
}
