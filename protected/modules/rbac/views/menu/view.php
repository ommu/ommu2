<?php
/**
 * @var $this yii\web\View
 * @var $model mdm\admin\models\Menu
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 28 December 2017, 06:50 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->context->layout = 'assignment';
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

if(!$small) {
$updateUrl = Url::to(['update', 'id' => $model->id]);
$deleteUrl = Url::to(['delete', 'id' => $model->id]);
$createUrl = Url::to(['create']);
if(($app = Yii::$app->request->get('app')) != null) {
	$updateUrl = Url::to(['update', 'id' => $model->id, 'app' => $app]);
	$deleteUrl = Url::to(['delete', 'id' => $model->id, 'app' => $app]);
	$createUrl = Url::to(['create', 'app' => $app]);
}
$this->params['menu']['content'] = [
	['label' => Yii::t('rbac-admin', 'Update'), 'url' => $updateUrl, 'icon' => 'pencil', 'htmlOptions' => ['class' => 'btn btn-primary']],
	['label' => Yii::t('rbac-admin', 'Delete'), 'url' => $deleteUrl, 'htmlOptions' => ['data-confirm' => Yii::t('rbac-admin', 'Are you sure to delete this item?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
	['label' => Yii::t('rbac-admin', 'Create'), 'url' => $createUrl, 'icon' => 'plus-square', 'htmlOptions' => ['class' => 'btn btn-success']],
];
} ?>

<div class="menu-view">

<?php
$attributes = [
    'menuParent.name:text:Parent',
    'name',
    [
        'attribute' => 'icon',
        'value' => function($model) {
            return '<i class="fa '.$model->icon.'"></i> '.$model->icon;
        },
        'format' => 'html',
    ],
    'route',
    'order',
    [
        'attribute' => 'public',
        'value' => $this->filterYesNo($model->public),
    ],
];

echo DetailView::widget([
	'model' => $model,
	'attributes' => $attributes,
	'template' => '<tr><th style="width:25%">{label}</th><td>{value}</td></tr>',
]); ?>

</div>
