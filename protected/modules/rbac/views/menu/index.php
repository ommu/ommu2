<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\libraries\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel mdm\admin\models\searchs\Menu */

$this->context->layout = 'assignment';
$this->title = Yii::t('rbac-admin', 'Menus');
$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('rbac-admin', 'Create Menu'), 'url' => Url::to(['create']), 'icon' => 'plus-square'],
];
?>

<div class="menu-index">
	<?php Pjax::begin(); ?>

	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'layout' => '<div class="row"><div class="col-sm-12">{items}</div></div><div class="row sum-page"><div class="col-sm-5">{summary}</div><div class="col-sm-7">{pager}</div></div>',
		'columns' => [
			[
				'class' => 'yii\grid\SerialColumn',
			],
			'name',
			[
				'attribute' => 'menuParent.name',
				'filter' => Html::activeTextInput($searchModel, 'parent_name', [
					'class' => 'form-control', 'id' => null
				]),
				'label' => Yii::t('rbac-admin', 'Parent'),
			],
			'route',
			'order',
			[
				'class' => 'yii\grid\ActionColumn',
				'header' => Yii::t('app', 'Option'),
				'contentOptions' => [
					'class'=>'action-column',
				],
			],
		],
	]);?>

	<?php Pjax::end(); ?>
</div>
