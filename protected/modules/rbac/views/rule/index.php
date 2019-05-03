<?php
/**
 * @var $this  yii\web\View
 * @var $model mdm\admin\models\BizRule
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $searchModel mdm\admin\models\searchs\BizRule
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 28 December 2017, 06:50 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\helpers\Url;
use app\components\grid\GridView;
use yii\widgets\Pjax;

$this->context->layout = 'assignment';
$this->title = Yii::t('rbac-admin', 'Rules');
$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('rbac-admin', 'Create Rule'), 'url' => Url::to(['create']), 'icon' => 'plus-square', 'htmlOptions' => ['class'=>'btn btn-success']],
];
?>

<div class="role-index">
	<?php Pjax::begin(); ?>

	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
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
