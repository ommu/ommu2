<?php
/**
 * @var $this yii\web\View
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $searchModel mdm\admin\models\searchs\Assignment
 * @var $usernameField string
 * @var $extraColumns string[]
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 28 December 2017, 06:50 WIB
 * @link https://github.com/ommu/ommu2
 *
 */

use yii\helpers\Html;
use app\components\grid\GridView;
use yii\widgets\Pjax;

$this->context->layout = 'assignment';
$this->title = Yii::t('rbac-admin', 'Assignments');
$this->params['breadcrumbs'][] = $this->title;

$columns = [
	[
		'class' => 'yii\grid\SerialColumn',
	],
	$usernameField,
];
if (!empty($extraColumns))
	$columns = array_merge($columns, $extraColumns);
$columns[] = [
	'class' => 'app\components\grid\ActionColumn',
	'header' => Yii::t('app', 'Option'),
	'template' => '{view}'
];
?>

<div class="assignment-index">
	<?php Pjax::begin(); ?>

	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => $columns,
	]); ?>

	<?php Pjax::end(); ?>

</div>
