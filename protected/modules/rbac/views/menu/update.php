<?php
/**
 * @var $this yii\web\View
 * @var $model mdm\admin\models\Menu
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 28 December 2017, 06:50 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\helpers\Url;

$this->context->layout = 'assignment';
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rbac-admin', 'Update');

if (!$small) {
    $viewUrl = Url::to(['view', 'id' => $model->id]);
    $deleteUrl = Url::to(['delete', 'id' => $model->id]);
    $createUrl = Url::to(['create']);
    if (($app = Yii::$app->request->get('app')) != null) {
        $viewUrl = Url::to(['view', 'id' => $model->id, 'app' => $app]);
        $deleteUrl = Url::to(['delete', 'id' => $model->id, 'app' => $app]);
        $createUrl = Url::to(['create', 'app' => $app]);
    }
    $this->params['menu']['content'] = [
        ['label' => Yii::t('rbac-admin', 'Detail'), 'url' => $viewUrl, 'icon' => 'eye', 'htmlOptions' => ['class' => 'btn btn-info']],
        ['label' => Yii::t('rbac-admin', 'Delete'), 'url' => $deleteUrl, 'htmlOptions' => ['data-confirm' => Yii::t('rbac-admin', 'Are you sure to delete this item?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
        ['label' => Yii::t('rbac-admin', 'Create'), 'url' => $createUrl, 'icon' => 'plus-square', 'htmlOptions' => ['class' => 'btn btn-success']],
    ];
} ?>

<div class="menu-update">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>