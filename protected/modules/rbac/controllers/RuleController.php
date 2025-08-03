<?php
/**
 * RuleController
 * @var $this app\modules\rbac\controllers\RuleController
 * @see $this extend mdm\admin\controllers\RuleController
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 7 December 2019, 18:13 WIB
 * @link https://github.com/ommu/ommu2
 *
 */
 
namespace app\modules\rbac\controllers;

use Yii;
use mdm\admin\components\AccessControl;
use yii\helpers\ArrayHelper;

class RuleController extends \mdm\admin\controllers\RuleController
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
