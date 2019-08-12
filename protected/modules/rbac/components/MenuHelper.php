<?php
/**
 * MenuHelper
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 2 January 2018, 23:08 WIB
 * @link https://github.com/ommu/ommu
 *
 */

namespace app\modules\rbac\components;

use Yii;
use yii\helpers\ArrayHelper;

class MenuHelper extends \mdm\admin\components\MenuHelper
{
	/**
	 * {@inheritdoc}
	 */
	public static function requiredParent($assigned, &$menus)
	{
		$assigned = ArrayHelper::merge($assigned, static::getPublicManu($menus));
		$l = count($assigned);
		for ($i = 0; $i < $l; $i++) {
			$id = $assigned[$i];
			$parent_id = $menus[$id]['parent'];
			if ($parent_id !== null && !in_array($parent_id, $assigned)) {
				$assigned[$l++] = $parent_id;
			}
		}

		return $assigned;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function normalizeMenu(&$assigned, &$menus, $callback, $parent = null)
	{
		$result = [];
		$order = [];
		foreach ($assigned as $id) {
			$menu = $menus[$id];
			if ($menu['parent'] == $parent) {
				$menu['children'] = static::normalizeMenu($assigned, $menus, $callback, $id);
				if ($callback !== null) {
					$item = call_user_func($callback, $menu);
				} else {
					$item = [
						'label' => $menu['name'],
						'url' => static::parseRoute($menu['route']),
						'icon' => $menu['icon'],
					];
					if ($menu['children'] != []) {
						$item['items'] = $menu['children'];
					}
				}
				$result[] = $item;
				$order[] = $menu['order'];
			}
		}
		if ($result != []) {
			array_multisort($order, $result);
		}

		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function getPublicManu($menus)
	{
		$menus = ArrayHelper::map($menus, 'id', 'public');

		$assigned = [];
		if(is_array($menus) && !empty($menus)) {
			foreach ($menus as $key => $val) {
				if($val == 1)
					$assigned[] = $key;
			}
		}

		return $assigned;
	}
}
