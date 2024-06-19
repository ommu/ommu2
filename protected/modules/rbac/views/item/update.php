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

use yii\helpers\Url;

$context = $this->context;
$labels = $context->labels();
$context->layout = 'assignment';
$this->title = Yii::t('rbac-admin', 'Update ' . $labels['Item']) . ': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', $labels['Items']), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = Yii::t('rbac-admin', 'Update');
?>

<div class="auth-item-update">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>