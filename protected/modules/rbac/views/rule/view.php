<?php
/**
 * @var yii\web\View $this
 * @var mdm\admin\models\AuthItem $model
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
use yii\widgets\DetailView;

$this->context->layout = 'assignment';
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Rules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

if(!$small) {
$this->params['menu']['content'] = [
	['label' => Yii::t('rbac-admin', 'Update'), 'url' => Url::to(['update', 'id'=>$model->name]), 'icon' => 'pencil', 'htmlOptions' => ['class'=>'btn btn-primary']],
	['label' => Yii::t('rbac-admin', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->name]), 'htmlOptions' => ['data-confirm'=>Yii::t('rbac-admin', 'Are you sure to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon'=>'trash'],
];
} ?>

<div class="auth-item-view">

<?php echo DetailView::widget([
	'model' => $model,
	'attributes' => [
		'name',
		'className',
	],
]); ?>

</div>
