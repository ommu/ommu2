<?php
/**
 * RouteController
 * @var $this app\modules\rbac\controllers\RouteController
 * @see $this extend mdm\admin\controllers\RouteController
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 7 December 2019, 18:13 WIB
 * @link https://github.com/ommu/ommu
 *
 */
 
namespace app\modules\rbac\controllers;

use Yii;
use mdm\admin\components\AccessControl;
use yii\helpers\ArrayHelper;

class RouteController extends \mdm\admin\controllers\RouteController
{
	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return ArrayHelper::merge(parent::behaviors(), [
			'access' => [
				'class' => AccessControl::className(),
			]
		]);
	}
}
