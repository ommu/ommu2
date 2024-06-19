<?php
/**
 * @var $this yii\web\View
 * @var $model mdm\admin\models\AuthItem
 * @var $context mdm\admin\components\ItemController
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 28 December 2017, 06:50 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use mdm\admin\AnimateAsset;
use yii\helpers\Json;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

$context = $this->context;
$labels = $context->labels();
$context->layout = 'assignment';
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', $labels['Items']), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

AnimateAsset::register($this);
YiiAsset::register($this);
$opts = Json::htmlEncode([
	'items' => $model->getItems(),
]);
$this->registerJs("var _opts = {$opts};");
$this->registerJs($this->render('_script.js'));
$animateIcon = ' <i class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></i>';

$small = $small ?? false;
if (!$small) {
    $this->params['menu']['content'] = [
        ['label' => Yii::t('rbac-admin', 'Update'), 'url' => Url::to(['update', 'id' => $model->name]), 'icon' => 'pencil', 'htmlOptions' => ['class' => 'btn btn-primary']],
        ['label' => Yii::t('rbac-admin', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->name]), 'htmlOptions' => ['data-confirm' => Yii::t('rbac-admin', 'Are you sure to delete this item?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
        ['label' => Yii::t('rbac-admin', 'Create'), 'url' => Url::to(['create']), 'icon' => 'plus-square', 'htmlOptions' => ['class' => 'btn btn-success']],
    ];
} ?>

<div class="auth-item-view">

<?php
$attributes = [
    'name',
    'description:ntext',
    'ruleName',
    'data:ntext',
];

echo DetailView::widget([
	'model' => $model,
	'attributes' => $attributes,
	'template' => '<tr><th style="width:25%">{label}</th><td>{value}</td></tr>',
]); ?>

<div class="x_panel">
	<div class="x_content">
		<div class="row">
			<div class="col-sm-5">
				<input class="form-control search" data-target="available"
					placeholder="<?php echo Yii::t('rbac-admin', 'Search for available');?>">
				<select multiple size="20" class="form-control list" data-target="available"></select>
			</div>
			<div class="col-sm-2 text-center">
				<br><br>
				<?php echo Html::a('&gt;&gt;' . $animateIcon, ['assign', 'id' => $model->name], [
					'class' => 'btn btn-success btn-assign',
					'data-target' => 'available',
					'title' => Yii::t('rbac-admin', 'Assign'),
				]);?>
				<br><br>
				<?php echo Html::a('&lt;&lt;' . $animateIcon, ['remove', 'id' => $model->name], [
					'class' => 'btn btn-danger btn-assign',
					'data-target' => 'assigned',
					'title' => Yii::t('rbac-admin', 'Remove'),
				]);?>
			</div>
			<div class="col-sm-5">
				<input class="form-control search" data-target="assigned"
					placeholder="<?php echo Yii::t('rbac-admin', 'Search for assigned');?>">
				<select multiple size="20" class="form-control list" data-target="assigned"></select>
			</div>
		</div>
	</div>
</div>

</div>
