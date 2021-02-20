<?php
/**
 * @var $this yii\web\View
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $searchModel mdm\admin\models\searchs\AuthItem
 * @var $context mdm\admin\components\ItemController
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 28 December 2017, 06:50 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\helpers\Url;
use app\components\grid\GridView;
use mdm\admin\components\RouteRule;
use mdm\admin\components\Configs;
use yii\widgets\Pjax;

$context = $this->context;
$labels = $context->labels();
$context->layout = 'assignment';
$this->title = Yii::t('rbac-admin', $labels['Items']);
$this->params['breadcrumbs'][] = $this->title;

$rules = array_keys(Configs::authManager()->getRules());
$rules = array_combine($rules, $rules);
unset($rules[RouteRule::RULE_NAME]);

$this->params['menu']['content'] = [
	['label' => Yii::t('rbac-admin', 'Create ' . $labels['Item']), 'url' => Url::to(['create']), 'icon' => 'plus-square', 'htmlOptions' => ['class' => 'btn btn-success']],
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
				'attribute' => 'ruleName',
				'label' => Yii::t('rbac-admin', 'Rule Name'),
				'filter' => $rules
			],
			[
				'attribute' => 'description',
				'label' => Yii::t('rbac-admin', 'Description'),
			],
			[
				'class' => 'app\components\grid\ActionColumn',
				'header' => Yii::t('app', 'Option'),
			],
		],
	]);?>

	<?php Pjax::end(); ?>
</div>
