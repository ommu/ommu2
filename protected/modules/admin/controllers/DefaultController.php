<?php
/**
 * DefaultController
 * @var $this yii\web\View
 *
 * Default controller for the `admin` module
 * Reference start
 * TOC :
 *	Index
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 22 December 2018, 03:29 WIB
 * @link https://github.com/ommu/ommu
 *
 */

namespace app\modules\admin\controllers;

use app\components\Controller;

class DefaultController extends Controller
{
	/**
	 * Renders the index view for the module
	 * @return string
	 */
	public function actionIndex()
	{
		$this->view->title = 'admins';
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('index');
	}
}
