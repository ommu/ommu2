<?php
/**
 * @var $this app\components\View
 * @var $this app\modules\admin\controllers\ViewLogController
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 13 January 2023, 19:34 WIB
 * @link https://www.ommu.id
 *
 */

use yii\helpers\Html;
use yii\data\Pagination;
use yii\widgets\LinkPager;

if ($totalPage > 1) {
    $pager = new Pagination([
        'totalCount' => $maxLines, 
        'pageSize' => $perPage,
    ]);

    echo LinkPager::widget([
        'pagination' => $pager,
    ]);
}
?>