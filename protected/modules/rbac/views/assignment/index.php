<?php
/**
 * @var $this yii\web\View
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $searchModel mdm\admin\models\searchs\Assignment
 * @var $usernameField string
 * @var $extraColumns string[]
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 28 December 2017, 06:50 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\helpers\Html;
use app\components\grid\GridView;
use yii\widgets\Pjax;

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
