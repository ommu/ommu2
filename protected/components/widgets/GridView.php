<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\components\widgets;

use Yii;

/**
 * GridView for Backoffice Themes
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 2 September 2017, 15:42 WIB
 * @link https://github.com/ommu/ommu
 */
class GridView extends \yiister\gentelella\widgets\grid\GridView
{
	use \ommu\traits\ThemeTrait;

	/**
	 * {@inheritdoc}
	 */
	public function run()
	{
		parent::run();

		if($this->view->theme->name != 'gentelella') {
			$gridViewIgnore = $this->view->themeSetting['gridview_ignore'];
			unset($this->view->assetBundles['yiister\gentelella\assets\ThemeBuildAsset']);
			unset($this->view->assetBundles['yiister\gentelella\widgets\grid\GridViewAsset']);

			if(isset($gridViewIgnore) && is_array($gridViewIgnore) && !empty($gridViewIgnore)) {
				foreach ($gridViewIgnore as $val) {
					if($val == 'bootstrap')
						unset($this->view->assetBundles['yii\bootstrap\BootstrapAsset']);
					else if($val == 'fontawesome')
						unset($this->view->assetBundles['rmrevin\yii\fontawesome\AssetBundle']);
				}
			}
		}
	}
}
