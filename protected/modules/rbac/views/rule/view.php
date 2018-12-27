<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var mdm\admin\models\AuthItem $model
 */
$this->context->layout = 'assignment';
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Rules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('rbac-admin', 'Update'), 'url' => Url::to(['update', 'id'=>$model->name]), 'icon' => 'pencil'],
	['label' => Yii::t('rbac-admin', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->name]), 'htmlOptions' => ['data-confirm'=>Yii::t('rbac-admin', 'Are you sure to delete this item?'), 'data-method'=>'post'], 'icon'=>'trash'],
];
?>

<div class="auth-item-view">

<?php echo DetailView::widget([
	'model' => $model,
	'attributes' => [
		'name',
		'className',
	],
]); ?>

</div>
