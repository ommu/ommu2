<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\AuthItem */
/* @var $context mdm\admin\components\ItemController */

$context = $this->context;
$labels = $context->labels();
$context->layout = 'assignment';
$this->title = Yii::t('rbac-admin', 'Update ' . $labels['Item']) . ': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', $labels['Items']), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = Yii::t('rbac-admin', 'Update');

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index']), 'icon' => 'table'],
];
?>

<div class="x_panel">
	<div class="x_content">
		<div class="auth-item-update">

		<?php echo $this->render('_form', [
			'model' => $model,
		]); ?>

		</div>
	</div>
</div>