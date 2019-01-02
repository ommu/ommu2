<?php
/**
 * Menu
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 2 January 2018, 15:58 WIB
 * @link https://github.com/ommu/ommu
 *
 */

namespace app\models;

use Yii;
use mdm\admin\models\Menu as MdmMenu;

class Menu extends MdmMenu
{
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		$rules = parent::rules();

		return \yii\helpers\ArrayHelper::merge($rules, [
			[['icon'], 'string', 'max' => 64],
		]);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		$attributes = parent::attributeLabels();

		return \yii\helpers\ArrayHelper::merge($attributes, [
			'icon' => Yii::t('app', 'Icon'),
		]);
	}
}
