<?php
/**
 * MenuHelper
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 2 January 2018, 23:08 WIB
 * @link https://github.com/ommu/ommu
 *
 */

namespace app\modules\rbac\components;

use Yii;
use mdm\admin\components\MenuHelper as MdmMenuHelper;

class MenuHelper extends MdmMenuHelper
{
	/**
	 * Normalize menu
	 * @param  array $assigned
	 * @param  array $menus
	 * @param  Closure $callback
	 * @param  integer $parent
	 * @return array
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
}
