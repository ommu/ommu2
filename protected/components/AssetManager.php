<?php
/**
 * AssetManager class
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 11 December 2019, 05:17 WIB
 * @link https://github.com/ommu/ommu
 */

namespace app\components;

use Yii;
use yii\helpers\Url;
use \yii\helpers\FileHelper;

class AssetManager extends \yii\web\AssetManager
{
	/**
	 * (@inheritdoc)
	 */
	public function clear()
	{
		if ($this->basePath == '')
			return;

		foreach (scandir($this->basePath) as $file) {
			if (substr($file, 0, 1) == '.')
				continue;

			FileHelper::removeDirectory($this->basePath . DIRECTORY_SEPARATOR . $file);
		}
	}

	/**
	 * (@inheritdoc)
	 */
	protected function publishDirectory($src, $options)
	{
		$dir = $this->hash($src);
		$dstDir = $this->basePath . DIRECTORY_SEPARATOR . $dir;

		//var_dump($options);
		if (!empty($options['forceCopy']) || ($this->forceCopy && !isset($options['forceCopy'])) || !is_dir($dstDir)) {
			$rand = $this->generateRandomString();
			
			//create version di apps.json
			if (!file_exists(join('/', [Yii::getAlias('@webroot'), 'apps.json']))) {
				file_put_contents(join('/', [Yii::getAlias('@webroot'), 'apps.json']), json_encode(['version'=>$rand]));
				@chmod(join('/', [Yii::getAlias('@webroot'), 'apps.json']), 0777);

			} else
				file_put_contents(join('/', [Yii::getAlias('@webroot'), 'apps.json']), json_encode(['version'=>$rand]));
		}

		return parent::publishDirectory($src, $options);
	}

	/**
	 * (@inheritdoc)
	 */
	public function generateRandomString($length = 10)
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}

		$str = file_get_contents(join('/', [Url::base(true), 'apps.json']));
		$data = json_decode($str);
		$dataVersion = $data->version;
		if ($randomString === $dataVersion)
			$randomString = $this->generateRandomString();

		return $randomString;
	}
}
