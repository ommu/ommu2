<?php

use yii\helpers\Html;
use app\libraries\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel mdm\admin\models\searchs\Assignment */
/* @var $usernameField string */
/* @var $extraColumns string[] */

$this->context->layout = 'assignment';
$this->title = Yii::t('rbac-admin', 'Assignments');
$this->params['breadcrumbs'][] = $this->title;

$columns = [
	[
		'class' => 'yii\grid\SerialColumn',
	],
	$usernameField,
];
if (!empty($extraColumns))
	$columns = array_merge($columns, $extraColumns);
$columns[] = [
	'class' => 'yii\grid\ActionColumn',
	'header' => Yii::t('app', 'Option'),
	'contentOptions' => [
		'class'=>'action-column',
	],
	'template' => '{view}'
];
?>

<div class="assignment-index">
	<?php Pjax::begin(); ?>

	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'layout' => '<div class="row"><div class="col-sm-12">{items}</div></div><div class="row sum-page"><div class="col-sm-5">{summary}</div><div class="col-sm-7">{pager}</div></div>',
		'columns' => $columns,
	]); ?>

	<?php Pjax::end(); ?>

</div>
