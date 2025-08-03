<?php
/**
 * MenuHelper
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 2 January 2018, 23:08 WIB
 * @link https://github.com/ommu/ommu2
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
		$assigned = static::getPublicManu($menus, $assigned);

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
					$url = static::parseRoute($menu['route']);
					if ($menu['data']) {
						parse_str(trim($menu['data']), $data);
						$url = ArrayHelper::merge($url, $data);
					}
					$item = [
						'label' => $menu['name'],
						'url' => $url,
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
	public static function getPublicManu($menus, $assigned)
	{
		$menus = ArrayHelper::map($menus, 'id', 'public');

		if (is_array($menus) && !empty($menus)) {
			foreach ($menus as $key => $val) {
				if ($val == 1 && !in_array($key, $assigned)) {
					$assigned[] = $key;
                }
			}
		}

		return $assigned;
	}
}
