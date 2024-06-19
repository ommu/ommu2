<?php
/**
 * MenuOption class for backend themes
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 2 September 2017, 15:42 WIB
 * @link https://github.com/ommu/ommu
 */

namespace app\components\widgets;

use yii\helpers\Html;

class MenuOption extends \yii\widgets\Menu
{
	/**
	 * {@inheritdoc}
	 */
	public $labelTemplate = '{label}';

	/**
	 * {@inheritdoc}
	 */
	public $linkTemplate = '<a href="{url}" title="{label}">{label}</a>';

	/**
	 * {@inheritdoc}
	 */
	public $activateParents = false;

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		Html::addCssClass($this->options, 'dropdown-menu');
		parent::init();
	}
}
