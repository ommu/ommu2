<?php
/**
 * Source Messages (source-message)
 * @var $this app\components\View
 * @var $this app\controllers\PhraseController
 * @var $model app\models\SourceMessage
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 6 December 2019, 10:32 WIB
 * @link https://github.com/ommu/ommu2
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;

if (!$small) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Development Tools'), 'url' => ['admin/module/manage']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Phrase'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = $model->message;

    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id' => $model->id]), 'icon' => 'pencil', 'htmlOptions' => ['class' => 'btn btn-info']],
        ['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->id]), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="source-message-view">

<?php
$attributes = [
	[
		'attribute' => 'id',
		'value' => $model->id ? $model->id : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'category',
		'value' => $model->category ? $model->category : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'message',
		'value' => $model->message ? $model->message : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'location',
		'value' => $model->location ? $model->location : '-',
		'visible' => !$small,
	],
];

if (!empty($model->languages)) {
	$attributes = ArrayHelper::merge($attributes, [
		[
			'attribute' => 'translates',
			'value' => function ($model) {
				return $model::parseTranslate($model->translate);
			},
			'format' => 'html',
			'visible' => !$small,
		],
	]);
}

$attributes = ArrayHelper::merge($attributes, [
	[
		'attribute' => 'creation_date',
		'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'creationDisplayname',
		'value' => isset($model->creation) ? $model->creation->displayname : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'modified_date',
		'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'modifiedDisplayname',
		'value' => isset($model->modified) ? $model->modified->displayname : '-',
		'visible' => !$small,
	],
	[
		'attribute' => '',
		'value' => Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Update'), 'class' => 'btn btn-primary btn-sm']),
		'format' => 'html',
		'visible' => !$small && Yii::$app->request->isAjax ? true : false,
	],
]);


echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class' => 'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>