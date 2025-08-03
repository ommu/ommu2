<?php
/**
 * @var $this  yii\web\View
 * @var $model mdm\admin\models\BizRule
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 28 December 2017, 06:50 WIB
 * @link https://github.com/ommu/ommu2
 *
 */

use yii\helpers\Url;

$this->context->layout = 'assignment';
$this->title = Yii::t('rbac-admin', 'Create Rule');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Rules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="auth-item-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>