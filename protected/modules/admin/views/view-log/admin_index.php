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
?>

<div class="right_col" role="main">

<?php echo $this->renderWidget('_pager', [
    'title' => Yii::t('app', 'Pagination'),
    'contentMenu' => true,
    'breadcrumb' => false,
    'totalPage' => $totalPage,
    'maxLines' => $maxLines,
    'perPage' => $perPage,
]); ?>

<?php echo $this->renderWidget('_preview', [
    'title' => Yii::t('app', 'Preview'),
    'contentMenu' => true,
    'breadcrumb' => false,
    'logs' => $logs,
]); ?>

<?php echo $this->renderWidget('_files', [
    'pageId' => 'downloadLogs',
    'title' => Yii::t('app', 'Log Files'),
    'contentMenu' => true,
    'breadcrumb' => false,
    'logFiles' => $logFiles,
]); ?>

</div>