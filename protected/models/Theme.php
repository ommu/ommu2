<?php
/**
 * Themes
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 22 April 2019, 23:14 WIB
 * @link https://github.com/ommu/ommu
 *
 * Kelas ini untuk mennghandle tema.
 *
 */

namespace app\models;

use Yii;

class Theme extends \yii\base\Model
{
	use \ommu\traits\ThemeTrait;

	/**
	 * {@inheritdoc}
	 */
	public static function getThemes(): array
	{
		$themePath = Yii::getAlias(Yii::$app->params['themePath']);
		$result = [];
		foreach(scandir($themePath) as $themeId) {
			if($themeId == '.' || 
				$themeId == '..' ||
				is_file($themePath . DIRECTORY_SEPARATOR . $themeId)) {
					continue;
			}

			if(is_dir($themePath . DIRECTORY_SEPARATOR . $themeId))
				$result[$themeId] = self::themeParseYaml($themeId);
		}

		return $result;
	}
}