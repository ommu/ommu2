<?php
/**
 * Menu
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 2 January 2018, 15:58 WIB
 * @link https://github.com/ommu/ommu
 *
 */

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

class Menu extends \mdm\admin\models\Menu
{
	public $menuCode;

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		if(($app = Yii::$app->request->get('app')) != null) {
			$params = require(join('/', [dirname(Yii::getAlias('@webroot')), $app, 'app/config', 'params.php']));
			Yii::$app->params = ArrayHelper::merge(Yii::$app->params, $params);
		}
		return parent::init();
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		$rules = parent::rules();

		return \yii\helpers\ArrayHelper::merge($rules, [
			[['public'], 'integer'],
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
			'public' => Yii::t('app', 'Public Manu'),
			'icon' => Yii::t('app', 'Icon'),
		]);
	}

	/**
	 * function getMenus
	 */
	public static function getOmmuMenus() 
	{
		$model = self::find()->select(['id', 'name', 'module'])->all();

		return \yii\helpers\ArrayHelper::map($model, 'id', 'menuCode');
	}

	/**
	 * function getMenus
	 */
	public static function getParentId($menuCode) 
	{
		$menus = self::getOmmuMenus();
		$menuFlip = array_flip($menus);

		return $menuFlip[$menuCode];
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->menuCode = $this->name;
		if($this->module)
			$this->menuCode = join('#', [$this->name, $this->module]);
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord) {
				if($this->creation_id == null)
					$this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			} else {
				if($this->modified_id == null)
					$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			}
		}
		return true;
	}
}
