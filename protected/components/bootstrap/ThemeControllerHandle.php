<?php
/**
 * ThemeControllerHandle
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 20 February 2019, 17:05 WIB
 * @link http://github.com/ommu/ommu
 *
 */

namespace app\components\bootstrap;

use Yii;
use yii\base\BootstrapInterface;
use yii\helpers\ArrayHelper;

class ThemeControllerHandle implements BootstrapInterface
{
	/**
	 * Bootstrap ini akan otomatis mancari tema pada folder tema dan mendaftarkan controller yang terdapat didalamnya pada controllerMap
	 */
	public function bootstrap($app)
	{
		if(!$app->isDemoTheme())
			return;

		$themePath = Yii::getAlias($app->params['themePath']);
		if(!file_exists($themePath))
			return;
		
		$controllerMap = [];
		foreach(scandir($themePath) as $file) {
			$themeFile = $themePath . DIRECTORY_SEPARATOR . $file;
			if($file == '.' || 
				$file == '..' ||
				(is_file($themeFile))) {
					continue;
			}

			if(is_dir($themeFile)) {
				$controllerArray = $this->getControllers($file);
				if(is_array($controllerArray))
					$controllerMap = ArrayHelper::merge($controllerMap, $controllerArray);
			}
		}
		$app->controllerMap = $controllerMap;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getController($theme, $path, $sub=null)
	{
		$controllerMap = [];
		$controllerPath = Yii::getAlias($path);
		$pathArray = explode('/', $path);
		$lastPath = end($pathArray);
		foreach(scandir($controllerPath) as $file) {
			$controllerFile = $controllerPath . DIRECTORY_SEPARATOR . $file;
			if($file == '.' || 
				$file == '..' ||
				(is_file($controllerFile) && in_array($file, ['index.php','.DS_Store']))) {
					continue;
			}

			if(is_file($controllerFile)) {
				$controller = join('-', [$theme, strtolower(preg_replace('(Controller.php)', '', $file))]);
				if($lastPath != 'controllers')
					$controller = join('-', [$theme, $lastPath, strtolower(preg_replace('(Controller.php)', '', $file))]);
				$controllerClass = preg_replace('(.php)', '', $file);
				
				$nsClass = [
					sprintf('themes\%s\controllers', $theme), 
					$controllerClass,
				];
				if($sub != null) {
					$nsClass = [
						sprintf('themes\%s\controllers', $theme), 
						$sub,
						$controllerClass,
					];
				}
				$controllerMap[$controller] = [
					'class'=>join('\\', $nsClass),
				];

			} else if(is_dir($controllerFile)) {
				$subPath = join('/', [$path, $file]);
				$controllerMap = ArrayHelper::merge($controllerMap, $this->getController($theme, $subPath, $file));
			}
		}
		
		return $controllerMap;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getControllers($theme)
	{
		if($theme) {
			$controllerPath = sprintf('@themes/%s/controllers', $theme);
			if(file_exists(Yii::getAlias($controllerPath)))
				return $this->getController($theme, $controllerPath);
		} else
			return false;
	}
}
