<?php
namespace app\assets;

use yii\web\View;

class PdfJsAsset extends \yii\web\AssetBundle
{
	public $sourcePath = '@npm/pdfjs-dist';

	public $publishOptions = [
		'forceCopy' => YII_DEBUG? true: false,
	];

}