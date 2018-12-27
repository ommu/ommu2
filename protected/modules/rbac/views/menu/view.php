<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\Menu */

$this->context->layout = 'assignment';
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('rbac-admin', 'Update'), 'url' => Url::to(['update', 'id'=>$model->id]), 'icon' => 'pencil'],
	['label' => Yii::t('rbac-admin', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'htmlOptions' => ['data-confirm'=>Yii::t('rbac-admin', 'Are you sure to delete this item?'), 'data-method'=>'post'], 'icon'=>'trash'],
	['label' => Yii::t('rbac-admin', 'Create'), 'url' => Url::to(['create']), 'icon' => 'plus-square'],
];
?>
<div class="menu-view">

<?php echo DetailView::widget([
	'model' => $model,
	'attributes' => [
		'menuParent.name:text:Parent',
		'name',
		'route',
		'order',
	],
	'template' => '<tr><th style="width:25%">{label}</th><td>{value}</td></tr>',
]); ?>

</div>
