<?php
namespace app\components\widgets;

use Yii;
use yii\helpers\ArrayHelper;

class PreviewPDF extends \yii\base\Widget
{
	public $waterMark = true;
	public $waterMarkParam = [];

	public $url;

	public function init()
	{
		$waterMarkParam = [
			'text' => 'OMMU',
			'alpha' => '0.5',
			'color' => 'red',
		];
		if(isset($this->waterMarkParam) && !empty($this->waterMarkParam))
			$this->waterMarkParam = ArrayHelper::merge($waterMarkParam, $this->waterMarkParam);
		else
			$this->waterMarkParam = $waterMarkParam;

		if($this->waterMark == false)
			$this->waterMarkParam = [];
	}

	public function run() 
	{
		return $this->render('preview_pdf', [
			'url' => $this->url,
			'waterMark' => $this->waterMarkParam,
			'id' => $this->id
		]);
	}
}