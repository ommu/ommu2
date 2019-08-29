<?php
/**
 * @var $this yii\web\View
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $searchModel mdm\admin\models\searchs\Menu
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 28 December 2017, 06:50 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\grid\GridView;
use yii\widgets\Pjax;

$this->context->layout = 'assignment';
$this->params['breadcrumbs'][] = $this->title;

$createUrl = Url::to(['create']);
if(($app = Yii::$app->request->get('app')) != null)
	$createUrl = Url::to(['create', 'app'=>$app]);
$this->params['menu']['content'] = [
	['label' => Yii::t('rbac-admin', 'Create Menu'), 'url' => $createUrl, 'icon' => 'plus-square', 'htmlOptions' => ['class'=>'btn btn-success']],
];
?>

<div class="menu-index">
<?php Pjax::begin(); ?>

<?php 
$columnData = [
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
];

array_push($columnData, [
	'class' => 'app\components\grid\ActionColumn',
	'header' => Yii::t('app', 'Option'),
	'urlCreator' => function($action, $model, $key, $index) {
		if($action == 'view') {
			if(($app = Yii::$app->request->get('app')) != null)
				return Url::to(['view', 'id'=>$key, 'app'=>$app]);
			return Url::to(['view', 'id'=>$key]);
		}
		if($action == 'update') {
			if(($app = Yii::$app->request->get('app')) != null)
				return Url::to(['update', 'id'=>$key, 'app'=>$app]);
			return Url::to(['update', 'id'=>$key]);
		}
		if($action == 'delete') {
			if(($app = Yii::$app->request->get('app')) != null)
				return Url::to(['delete', 'id'=>$key, 'app'=>$app]);
			return Url::to(['delete', 'id'=>$key]);
		}
	},
	'template' => '{view} {update} {delete}',
]);

echo GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'columns' => $columnData,
]);?>

<?php Pjax::end(); ?>
</div>
