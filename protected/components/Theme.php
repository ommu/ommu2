<?php
/**
 * Theme class
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 27 April 2019, 00:42 WIB
 * @link https://github.com/ommu/ommu
 * 
 */

namespace app\components;

use Yii;

class Theme extends \yii\base\Theme
{
	use \ommu\traits\ThemeTrait;

	/**
	 * {@inheritdoc}
	 */
	public function getName() {
		$themePath = preg_replace("/(\/)/", '\/', Yii::getAlias('@themes'));

		return preg_replace("/(\/)/", '', preg_replace("/^($themePath)/", '', $this->basePath));
	}

	/**
	 * {@inheritdoc}
	 */
	public static function getThemes(): array
	{
		$themePath = Yii::getAlias(Yii::$app->params['themePath']);
		$result = [];
		foreach(scandir($themePath) as $themeId) {
			if ($themeId == '.' || 
				$themeId == '..' ||
				is_file($themePath . DIRECTORY_SEPARATOR . $themeId)) {
					continue;
			}

			if (is_dir($themePath . DIRECTORY_SEPARATOR . $themeId)) {
				$result[$themeId] = self::themeInfo($themeId);
            }
		}

		return $result;
	}
}