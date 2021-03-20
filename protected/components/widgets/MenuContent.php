<?php
/**
 * ContentMenu class for backend themes
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 2 September 2017, 15:42 WIB
 * @link https://github.com/ommu/ommu
 */

namespace app\components\widgets;

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
	public $linkTemplate = "<a href=\"{url}\" title=\"{label}\" {htmlOptions}>{icon} {label}</a>";

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
		Html::addCssClass($this->options, 'content-menu');
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
			]
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function htmlOptions($array)
	{
		$htmlOptions = '';
		if (!empty($array)) {
			foreach ($array as $key => $value) {
				if ($key == 'class') {
					$value = join(' ', [$value, $this->view->themeSetting['content_menu_class']]);
                }
				$htmlOptions .= $key.'="'.$value.'"';
			}
		}
		return $htmlOptions;
	}
}
