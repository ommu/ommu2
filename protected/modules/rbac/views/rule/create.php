<?php

use yii\helpers\Url;

/* @var $this  yii\web\View */
/* @var $model mdm\admin\models\BizRule */

$this->context->layout = 'assignment';
$this->title = Yii::t('rbac-admin', 'Create Rule');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Rules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index']), 'icon' => 'table'],
];
?>

<div class="x_panel">
	<div class="x_content">
		<div class="auth-item-create">

		<?php echo $this->render('_form', [
			'model' => $model,
		]); ?>

		</div>
	</div>
</div>