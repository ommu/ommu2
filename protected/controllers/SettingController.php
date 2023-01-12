<?php
/**
 * SettingController
 * @var $this app\components\View
 *
 * Reference start
 * TOC :
 *	Update
 *	Meta
 *	Sublayout
 *	Pagination
 *	Reset
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 22 April 2019, 23:47 WIB
 * @link https://github.com/ommu/ommu
 *
 */

namespace app\controllers;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use app\models\BaseSetting;
use app\components\Theme;
use app\models\MetaSetting;

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
	 * {@inheritdoc}
	 */
	public function allowAction(): array {
		return ['reset'];
	}

	/**
	 * Update Action
	 */
	public function actionUpdate()
	{
		$app = Yii::$app->request->get('app');
		$model = new BaseSetting(['app' => $app ? $app : Yii::$app->id]);

		$themes = [];
		foreach($allTheme = Theme::getThemes() as $key => $val) {
			$themes[$key] = $val ? $val['desc'] : ucwords($key);
		}

		$backSubLayout = $allTheme[$model->backoffice_theme]['sublayout'] ?? [];
		$backPagination = $allTheme[$model->backoffice_theme]['pagination'] ?? [];
		$backLoginLayout = $allTheme[$model->backoffice_theme]['loginlayout'] ?? [];
		$maintenanceSubLayout = $allTheme[$model->maintenance_theme]['sublayout'] ?? [];
		$frontSubLayout = $allTheme[$model->theme]['sublayout'] ?? [];
		$frontPagination = $allTheme[$model->theme]['pagination'] ?? [];
		$frontLoginLayout = $allTheme[$model->theme]['loginlayout'] ?? [];

		if (Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			if ($model->save()) {
				$name = unserialize($model->name);
				$success = Yii::t('app', 'General setting success updated.');
				if ($app != null) {
					$success = Yii::t('app', 'App setting <strong>{app-name}</strong> success updated.', ['app-name' => $name['long']]);
                }
				Yii::$app->session->setFlash('success', $success);
				if ($app != null) {
					return $this->redirect(['update', 'app' => $app]);
                }
				return $this->redirect(['update']);

			} else {
				if (Yii::$app->request->isAjax) {
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
			}
		}

		$title = Yii::t('app', 'General Settings');
		$description = Yii::t('app', 'This page contains general settings that affect your entire {app-name} application.', ['app-name' => $model->name['small']]);
		if ($app != null) {
			$title = Yii::t('app', 'App Setting: {app-name}', ['app-name' => $model->name['small']]);
			$description = Yii::t('app', 'This page contains app settings that affect your entire {app-name} application.', ['app-name' => $model->name['long']]);
		}

		$this->view->title = $title;
		$this->view->description = $description;
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
			'themes' => $themes,
			'backSubLayout' => $backSubLayout,
			'backPagination' => $backPagination,
			'backLoginLayout' => $backLoginLayout,
			'maintenanceSubLayout' => $maintenanceSubLayout,
			'frontSubLayout' => $frontSubLayout,
			'frontPagination' => $frontPagination,
			'frontLoginLayout' => $frontLoginLayout,
		]);
	}

	/**
	 * Meta Action
	 */
	public function actionMeta()
	{
		$app = Yii::$app->request->get('app');
		$model = new MetaSetting(['app' => $app ? $app : Yii::$app->id]);
		$name = unserialize(Yii::$app->setting->get(join('_', [$model->app, 'name'])));

		if (Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			if ($model->save()) {
				$success = Yii::t('app', 'Meta setting success updated.');
				if ($app != null) {
					$success = Yii::t('app', 'App meta setting <strong>{app-name}</strong> success updated.', ['app-name' => $name['long']]);
                }
				Yii::$app->session->setFlash('success', $success);
				if ($app != null) {
					return $this->redirect(['meta', 'app' => $app]);
                }
				return $this->redirect(['meta']);

			} else {
				if (Yii::$app->request->isAjax) {
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
			}
		}

		$title = Yii::t('app', 'Meta Settings');
		$description = Yii::t('app', 'This page contains meta settings that affect your entire {app-name} application.', ['app-name' => $name['small']]);
		if ($app != null) {
			$title = Yii::t('app', 'Meta Setting: {app-name}', ['app-name' => $name['small']]);
			$description = Yii::t('app', 'This page contains meta settings that affect your entire {app-name} application.', ['app-name' => $name['long']]);
		}

		$this->view->title = $title;
		$this->view->description = $description;
		$this->view->keywords = '';
		return $this->render('admin_meta', [
			'model' => $model,
		]);
	}

	/**
	 * ThemeSublayout Action
	 */
	public function actionSublayout($theme)
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$yaml = Theme::themeInfo($theme);

		return is_array($yaml) ? ($yaml['sublayout'] ? $this->getSublayout($yaml['sublayout']) : []) : [];
	}

	/**
	 * ThemePagination Action
	 */
	public function actionPagination($theme)
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$yaml = Theme::themeInfo($theme);

		return is_array($yaml) ? ($yaml['pagination'] ? $this->getSublayout($yaml['pagination']) : []) : [];
	}

	/**
	 * ThemeLoginlayout Action
	 */
	public function actionLoginlayout($theme)
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$yaml = Theme::themeInfo($theme);

		return is_array($yaml) ? ($yaml['loginlayout'] ? $this->getSublayout($yaml['loginlayout']) : []) : [];
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

	/**
	 * {@inheritdoc}
	 */
	public function actionReset()
	{
		Yii::$app->setting->delete('reset');
		Yii::$app->meta->delete('reset');

		return $this->goBack();
	}
}
