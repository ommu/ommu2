<?php
/**
 * @var $this yii\web\View
 * @var $model mdm\admin\models\Menu
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 28 December 2017, 06:50 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->context->layout = 'assignment';
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('rbac-admin', 'Update'), 'url' => Url::to(['update', 'id'=>$model->id]), 'icon' => 'pencil', 'htmlOptions' => ['class'=>'btn btn-primary']],
	['label' => Yii::t('rbac-admin', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'htmlOptions' => ['data-confirm'=>Yii::t('rbac-admin', 'Are you sure to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon'=>'trash'],
	['label' => Yii::t('rbac-admin', 'Create'), 'url' => Url::to(['create']), 'icon' => 'plus-square', 'htmlOptions' => ['class'=>'btn btn-success']],
];
?>
<div class="menu-view">

<?php echo DetailView::widget([
	'model' => $model,
	'attributes' => [
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
	],
	'template' => '<tr><th style="width:25%">{label}</th><td>{value}</td></tr>',
]); ?>

</div>
