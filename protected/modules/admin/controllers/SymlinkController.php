<?php
/**
 * SymlinkController
 * @var $this app\components\View
 *
 * Reference start
 * TOC :
 *  Index
 *  Set
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 24 August 2022, 09:12 WIB
 * @link https://www.ommu.id
 *
 */

namespace app\modules\admin\controllers;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;

class SymlinkController extends Controller
{
    const SYMLINK_CMD = 'ln -sf';

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
		return [];
	}

	/**
	 * Index Action
	 */
	public function actionIndex()
	{
        throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'The requested page does not exist.'));
	}

	/**
	 * Set Action
	 */
	public function actionSet()
	{
        if (Yii::$app->request->isPost) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    
            $data = [
                // extension
                '/Users/putrasudaryanto/htdocs/project_ommu_module/gii_template-v2' => '/Users/putrasudaryanto/htdocs/_client/bpadjogja_v2/protected/vendor/ommu/gii',
                // '/Users/putrasudaryanto/htdocs/project_ommu_module/yii-traits-v2' => '/Users/putrasudaryanto/htdocs/_client/bpadjogja_v2/protected/vendor/ommu/yii-traits',
                '/Users/putrasudaryanto/htdocs/project_ommu_extension/yii2-redactor' => '/Users/putrasudaryanto/htdocs/_client/bpadjogja_v2/protected/vendor/ommu/yii2-redactor',
                '/Users/putrasudaryanto/htdocs/project_ommu_extension/yii2-selectize' => '/Users/putrasudaryanto/htdocs/_client/bpadjogja_v2/protected/vendor/ommu/yii2-selectize',
                '/Users/putrasudaryanto/htdocs/project_ommu_extension/yii2-flatpickr-v2' => '/Users/putrasudaryanto/htdocs/_client/bpadjogja_v2/protected/vendor/ommu/yii2-flatpickr',
                '/Users/putrasudaryanto/htdocs/project_ommu_extension/yii2-daterangepicker-v2' => '/Users/putrasudaryanto/htdocs/_client/bpadjogja_v2/protected/vendor/ommu/yii2-daterangepicker',
                '/Users/putrasudaryanto/htdocs/project_ommu_extension/yii2-dropzone' => '/Users/putrasudaryanto/htdocs/_client/bpadjogja_v2/protected/vendor/ommu/yii2-dropzone',
                '/Users/putrasudaryanto/htdocs/project_ommu_extension/yii2-gapi' => '/Users/putrasudaryanto/htdocs/_client/bpadjogja_v2/protected/vendor/ommu/gapi-google-analytics-php-interface',
                '/Users/putrasudaryanto/htdocs/project_ommu_extension/yii2-centrifugo' => '/Users/putrasudaryanto/htdocs/_client/bpadjogja_v2/protected/vendor/ommu/yii2-centrifugo',
                // module
                '/Users/putrasudaryanto/htdocs/project_ommu_module/module_users-v2' => '/Users/putrasudaryanto/htdocs/_client/bpadjogja_v2/protected/vendor/ommu/users',
                // '/Users/putrasudaryanto/htdocs/project_ommu_module/module_mailer-v2' => '/Users/putrasudaryanto/htdocs/_client/bpadjogja_v2/protected/vendor/ommu/mailer',
                '/Users/putrasudaryanto/htdocs/project_ommu_module/module_report-v2' => '/Users/putrasudaryanto/htdocs/_client/bpadjogja_v2/protected/vendor/ommu/report',
                '/Users/putrasudaryanto/htdocs/project_ommu_module/module_support-v2' => '/Users/putrasudaryanto/htdocs/_client/bpadjogja_v2/protected/vendor/ommu/support',
                '/Users/putrasudaryanto/htdocs/project_ommu_module/module_banner-v2' => '/Users/putrasudaryanto/htdocs/_client/bpadjogja_v2/protected/vendor/ommu/banner',
                '/Users/putrasudaryanto/htdocs/project_ommu_module/module_article-v2' => '/Users/putrasudaryanto/htdocs/_client/bpadjogja_v2/protected/vendor/ommu/article',
                '/Users/putrasudaryanto/htdocs/project_ommu_module/module_archive-v2' => '/Users/putrasudaryanto/htdocs/_client/bpadjogja_v2/protected/vendor/ommu/archive',
                '/Users/putrasudaryanto/htdocs/project_ommu_module/module_archive_location-v2' => '/Users/putrasudaryanto/htdocs/_client/bpadjogja_v2/protected/vendor/ommu/archive-location',
                '/Users/putrasudaryanto/htdocs/project_ommu_module/module_kckr-v2' => '/Users/putrasudaryanto/htdocs/_client/bpadjogja_v2/protected/vendor/ommu/kckr',
                '/Users/putrasudaryanto/htdocs/project_ommu_module/module_ppid-v2' => '/Users/putrasudaryanto/htdocs/_client/bpadjogja_v2/protected/vendor/ommu/ppid',
                '/Users/putrasudaryanto/htdocs/project_ommu_module/module_akreditasi-v2' => '/Users/putrasudaryanto/htdocs/_client/bpadjogja_v2/protected/vendor/ommu/akreditasi',
                //theme
                '/Users/putrasudaryanto/htdocs/project_ommu_theme/gentelella-v2' => '/Users/putrasudaryanto/htdocs/_client/bpadjogja_v2/protected/themes/gentelella',
                '/Users/putrasudaryanto/htdocs/project_ommu_theme/stackadmin-v2' => '/Users/putrasudaryanto/htdocs/_client/bpadjogja_v2/protected/themes/stackadmin',
                '/Users/putrasudaryanto/htdocs/project_ommu_theme/arnica-v2' => '/Users/putrasudaryanto/htdocs/_client/bpadjogja_v2/protected/themes/arnica',
                '/Users/putrasudaryanto/htdocs/project_ommu_theme/blueclean-v2' => '/Users/putrasudaryanto/htdocs/_client/bpadjogja_v2/protected/themes/blueclean',
                '/Users/putrasudaryanto/htdocs/project_ommu_theme/sandbox-v2/sandbox-master-v2' => '/Users/putrasudaryanto/htdocs/_client/bpadjogja_v2/protected/themes/sandbox'
            ];

            $model = new \app\components\jobs\SymlinkVendorJob([
                'user_id' => Yii::$app->user->id,
                'cmd' => self::SYMLINK_CMD,
                'args' => $data,
            ]);
            $model->execute();
        }

		$this->view->title = Yii::t('app', 'Create Symlink');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->renderModal('admin_set');
	}

}
