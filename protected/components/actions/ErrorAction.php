<?php
/**
 * ErrorAction class
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 1 August 2019, 05:54 WIB
 * @link https://github.com/ommu/ommu
 */

namespace app\components\actions;

class ErrorAction extends \yii\web\ErrorAction
{
	/**
	 * (@inheritdoc)
	 */
	public function getExceptionName()
	{
		return parent::getExceptionName();
	}

	/**
	 * (@inheritdoc)
	 */
	public function getExceptionMessage()
	{
		return parent::getExceptionMessage();
	}
}
