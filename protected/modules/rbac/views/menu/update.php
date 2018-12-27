<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\Menu */

$this->context->layout = 'assignment';
$this->title = Yii::t('rbac-admin', 'Update Menu') . ': ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rbac-admin', 'Update');

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index']), 'icon' => 'table'],
];
?>

<div class="x_panel">
	<div class="x_content">
		<div class="menu-update">

		<?php echo $this->render('_form', [
			'model' => $model,
		]); ?>

		</div>
	</div>
</div>