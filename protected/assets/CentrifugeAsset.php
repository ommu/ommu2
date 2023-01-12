<?php
namespace app\assets;

class CentrifugeAsset extends \yii\web\AssetBundle
{
	public $sourcePath = '@npm/centrifuge/dist';

	public $js = [
		'centrifuge.js',
	];

	public $publishOptions = [
		'forceCopy' => YII_DEBUG? true: false,
	];

}