<?php
namespace app\assets;

class EventEmitterAsset extends \yii\web\AssetBundle
{
	public $sourcePath = '@npm/wolfy87-eventemitter';

	public $js = [
		'EventEmitter.min.js',
	];

	public $publishOptions = [
		'forceCopy' => YII_DEBUG? true: false,
		'except' => [
			'docs/',
			'tests/',
			'tools/',
		],
	];

}