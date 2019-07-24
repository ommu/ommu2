<?php
/**
 * SettingManager class
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 December 2017, 15:51 WIB
 * @link https://github.com/ommu/ommu
 */

declare(strict_types=1);
namespace app\components;

use Yii;
use app\components\BaseSettingManager;

class SettingManager extends BaseSettingManager
{
	/**
	 * Register the robots meta
	 * $index must be index or noindex or empty/null
	 * $follow must be follow or nofollow or empty/null
	 * @param string $index
	 * @param string $follow
	 */
	public function setRobots($index = null, $follow = null)
	{
		$v = [];
		if (!empty($index))
			$v[] = $index;
		if (!empty($follow))
			$v[] = $follow;

		if (!empty($v)) {
			Yii::$app->view->registerMetaTag(['name' => 'robots', 'content' => strtolower(implode(',', $v))], 'robots');
			Yii::$app->view->registerMetaTag(['name' => 'googlebot', 'content' => strtolower(implode(',', $v))], 'googlebot');
		}
	}

	/**
	 * Register title meta and open graph title meta
	 * @param string $title
	 */
	public function setTitle($title)
	{
		if (!empty($title)) {
			Yii::$app->view->registerMetaTag(['name' => 'title', 'content' => $title], 'title');
			Yii::$app->view->registerMetaTag(['itemprop' => 'name', 'content' => $title], 'name');
			Yii::$app->view->registerMetaTag(['property' => 'og:type', 'content' => 'website'], 'og:type');
			Yii::$app->view->registerMetaTag(['property' => 'og:title', 'content' => $title], 'og:title');
			Yii::$app->view->registerMetaTag(['name' => 'twitter:card', 'content' => 'summary_large_image'], 'twitter:card');
			Yii::$app->view->registerMetaTag(['name' => 'twitter:title', 'content' => $title], 'twitter:title');
		}
	}

	/**
	 * Register description meta and open graph description meta
	 * @param string $description
	 */
	public function setDescription($description)
	{
		if (!empty($description)) {
			Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => $description], 'description');
			Yii::$app->view->registerMetaTag(['itemprop' => 'description', 'content' => $description], 'google:description');
			Yii::$app->view->registerMetaTag(['property' => 'og:description', 'content' => $description], 'og:description');
			Yii::$app->view->registerMetaTag(['name' => 'twitter:description', 'content' => $description], 'twitter:description');
		}
	}

	/**
	 * Register keywords meta
	 * @param string $keywords
	 */
	public function setKeywords($keywords)
	{
		if (!empty($keywords)) {
			Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => $keywords], 'keywords');
		}
	}

	/**
	 * Register keywords image
	 * @param string $imageUrl
	 */
	public function setImage($imageUrl)
	{
		if (!empty($imageUrl)) {
			Yii::$app->view->registerMetaTag(['name' => 'image', 'content' => $imageUrl], 'image');
			Yii::$app->view->registerMetaTag(['itemprop' => 'image', 'content' => $imageUrl], 'google:image');
			Yii::$app->view->registerMetaTag(['property' => 'og:image', 'content' => $imageUrl], 'og:image');
			Yii::$app->view->registerMetaTag(['name' => 'ttwitter:image', 'content' => $imageUrl], 'twitter:image');
		}
	}

	/**
	 * Register keywords image
	 * @param string $url
	 */
	public function setUrl($url)
	{
		if (!empty($url)) {
			Yii::$app->view->registerMetaTag(['property' => 'og:url', 'content' => $url], 'og:url');
		}
	}

}
