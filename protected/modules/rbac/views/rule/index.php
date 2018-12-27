<?php

use yii\helpers\Url;
use app\libraries\grid\GridView;
use yii\widgets\Pjax;

/* @var $this  yii\web\View */
/* @var $model mdm\admin\models\BizRule */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel mdm\admin\models\searchs\BizRule */

$this->context->layout = 'assignment';
$this->title = Yii::t('rbac-admin', 'Rules');
$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('rbac-admin', 'Create Rule'), 'url' => Url::to(['create']), 'icon' => 'plus-square'],
];
?>

<div class="role-index">
	<?php Pjax::begin(); ?>

	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'layout' => '<div class="row"><div class="col-sm-12">{items}</div></div><div class="row sum-page"><div class="col-sm-5">{summary}</div><div class="col-sm-7">{pager}</div></div>',
		'columns' => [
			[
				'class' => 'yii\grid\SerialColumn',
			],
			[
				'attribute' => 'name',
				'label' => Yii::t('rbac-admin', 'Name'),
			],
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
