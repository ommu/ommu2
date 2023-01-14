<?php
/**
 * @var $this yii\web\View
 * @var $this app\modules\admin\controllers\DashboardController
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 3 January 2018, 00:24 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Dashboard'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Summary');
?>

<div class="right_col" role="main">

<div class="row" style="display: inline-block;">
    <div class="tile_count">
        <div class="col-md-2 col-sm-4 tile_stats_count">
            <a href="">
                <span class="count_top"><i class="fa fa-user"></i> Total Users</span>
                <div class="count">2500</div>
                <span class="count_bottom"><i class="green">4% </i> From last Week</span>
            </a>
        </div>
        <div class="col-md-2 col-sm-4 tile_stats_count">
            <span class="count_top"><i class="fa fa-clock-o"></i> Average Time</span>
            <div class="count">123.50</div>
            <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>3% </i> From last Week</span>
        </div>
        <div class="col-md-2 col-sm-4 tile_stats_count">
            <span class="count_top"><i class="fa fa-user"></i> Total Males</span>
            <div class="count green">2,500</div>
            <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
        </div>
        <div class="col-md-2 col-sm-4 tile_stats_count">
            <span class="count_top"><i class="fa fa-user"></i> Total Females</span>
            <div class="count">4,567</div>
            <span class="count_bottom"><i class="red"><i class="fa fa-sort-desc"></i>12% </i> From last Week</span>
        </div>
        <div class="col-md-2 col-sm-4 tile_stats_count">
            <span class="count_top"><i class="fa fa-user"></i> Total Collections</span>
            <div class="count">2,315</div>
            <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
        </div>
        <div class="col-md-2 col-sm-4 tile_stats_count">
            <span class="count_top"><i class="fa fa-user"></i> Total Connections</span>
            <div class="count">7,325</div>
            <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4 col-sm-6 col-xs-12">
        <?php echo $this->renderWidget('_migrate', [
            'title' => Yii::t('app', 'Migrate'),
            'contentMenu' => true,
            'breadcrumb' => false,
        ]); ?>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12">
        <?php echo $this->renderWidget('_composer', [
            'title' => Yii::t('app', 'Composer'),
            'contentMenu' => true,
            'breadcrumb' => false,
        ]); ?>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12">
        <?php echo $this->renderWidget('_asset', [
            'title' => Yii::t('app', 'Assets'),
            'contentMenu' => true,
            'breadcrumb' => false,
        ]); ?>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12">
        <?php echo $this->renderWidget('_view_log', [
            'title' => Yii::t('app', 'Logs'),
            'contentMenu' => true,
            'breadcrumb' => false,
        ]); ?>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12">
        <?php echo $this->renderWidget('_others', [
            'title' => Yii::t('app', 'Others'),
            'contentMenu' => true,
            'breadcrumb' => false,
        ]); ?>
    </div>
</div>

</div>