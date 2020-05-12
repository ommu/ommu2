<?php
/**
 * ModuleAutoLoader
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 24 December 2017, 20:45 WIB
 * @link https://github.com/ommu/ommu
 *
 */

namespace app\components\bootstrap;

use Yii;
use yii\base\BootstrapInterface;

class ModuleAutoLoader implements BootstrapInterface
{
	const CACHE_ID = 'module_configs';

	/**
	 * Bootstrap ini akan otomatis mancari module pada folder module dan menjalankanya.
	 */
	public function bootstrap($app)
	{
		$modules = $app->cache->get(self::CACHE_ID);
		if($modules === false) {
			$modules = [];
			foreach($app->params['moduleAutoloadPaths'] as $modulePath) {
				$modulePath = Yii::getAlias($modulePath);
				foreach(scandir($modulePath) as $moduleId) {
					if($moduleId == '.' || 
						$moduleId == '..' ||
						is_file($modulePath . DIRECTORY_SEPARATOR . $moduleId) ||
						in_array($moduleId, $app->params['dontLoadModule'])) {
							continue;
					}

					$moduleDir = $modulePath . DIRECTORY_SEPARATOR . $moduleId;
					if(is_dir($moduleDir) && is_file($moduleDir . DIRECTORY_SEPARATOR . 'config.php')) {
						try {
							$modules[$moduleDir] = require($moduleDir . DIRECTORY_SEPARATOR . 'config.php');
						} catch(\Exception $ex) {
						}
					}
				}
			}
		}

		$app->moduleManager->registerBulk($modules);
	}
}
