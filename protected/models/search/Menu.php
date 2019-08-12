<?php
/**
 * Menu
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 5 August 2019, 15:58 WIB
 * @link https://github.com/ommu/ommu
 *
 */

namespace app\models\search;

use Yii;

class Menu extends \mdm\admin\models\searchs\Menu
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		$app = Yii::$app->request->get('app');
		if($app) {
			$params = require(join('/', [dirname(Yii::getAlias('@webroot')), $app, 'app/config', 'params.php']));
			return $params['mdm.admin.configs']['menuTable'];
		}
	
		return parent::tableName();
	}
}
