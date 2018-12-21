<?php
/**
 * ContentMenu class for backend themes
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 2 September 2017, 15:42 WIB
 * @link https://github.com/ommu/ommu
 */

namespace app\components\menu;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use rmrevin\yii\fontawesome\component\Icon;

class MenuContent extends \yii\widgets\Menu
{
	/**
	 * {@inheritdoc}
	 */
	public $labelTemplate = '{label}';

	/**
	 * {@inheritdoc}
	 */
	public $linkTemplate = "<a href=\"{url}\" title=\"{label}\" {htmlOptions}{data-confirm}{data-method}>{icon} {label}</a>";

	/**
	 * {@inheritdoc}
	 */
	public $submenuTemplate = "\n<ul class=\"nav child_menu\">\n{items}\n</ul>\n";

	/**
	 * {@inheritdoc}
	 */
	public $activateParents = true;

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		Html::addCssClass($this->options, 'nav content-menu');
		parent::init();
	}

	/**
	 * {@inheritdoc}
	 */
	protected function renderItem($item)
	{
		$renderedItem = parent::renderItem($item);
		return strtr(
			$renderedItem,
			[
				'{icon}' => isset($item['icon'])
					? new Icon($item['icon'], ArrayHelper::getValue($item, 'iconOptions', []))
					: '',
				'{htmlOptions}' => isset($item['htmlOptions'])
					? ' '.$this->htmlOptions($item['htmlOptions'])
					: '',
				'{data-confirm}' => isset($item['data-confirm'])
					? ' data-confirm="'.$item['data-confirm'].'"'
					: '',
				'{data-method}' => isset($item['data-method'])
					? ' data-method="'.$item['data-method'].'"'
					: '',
			]
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function htmlOptions($array)
	{
		$htmlOptions = '';
		if(!empty($array)) {
			foreach ($array as $key => $value) {
				$htmlOptions .= $key.'="'.$value.'"';
			}
		}
		return $htmlOptions;
	}
}
