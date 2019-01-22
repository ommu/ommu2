<?php
/**
 * Modules (modules)
 * @var $this yii\web\View
 * @var $this app\modules\admin\controllers\ModuleController
 * @var $model app\modules\admin\models\Modules
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 26 December 2017, 09:41 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Modules'), 'url' => ['manage']];
$this->params['breadcrumbs'][] = $model->id;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['manage']), 'icon' => 'table'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'method' => 'post', 'icon' => 'trash'],
];
?>

<div class="modules-view">

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'id',
		'module_id',
		[
			'attribute' => 'installed',
			'value' => $this->filterYesNo($model->installed),
		],
		[
			'attribute' => 'enabled',
			'value' => $model->getEnableCondition($model->enabled, $model->module_id) ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
		],
		[
			'attribute' => 'creation_date',
			'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
		],
		[
			'attribute' => 'creation_search',
			'value' => isset($model->creation) ? $model->creation->displayname : '-',
		],
		[
			'attribute' => 'modified_date',
			'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
		],
		[
			'attribute' => 'modified_search',
			'value' => isset($model->modified) ? $model->modified->displayname : '-',
		],
	],
]) ?>

</div>