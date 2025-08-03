<?php
/**
 * @var $this yii\web\View
 * @var $model mdm\admin\models\Assignment
 * @var $fullnameField string
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 28 December 2017, 06:50 WIB
 * @link https://github.com/ommu/ommu2
 *
 */

use mdm\admin\AnimateAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\YiiAsset;
use yii\helpers\Url;

$userName = $model->{$usernameField};
if (!empty($fullnameField)) {
	$userName .= ' (' . ArrayHelper::getValue($model, $fullnameField) . ')';
}
$userName = Html::encode($userName);

$this->context->layout = 'assignment';
$this->title = Yii::t('rbac-admin', 'Assignment') . ' : ' . $userName;

$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Assignments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $userName;

AnimateAsset::register($this);
YiiAsset::register($this);
$opts = Json::htmlEncode([
	'items' => $model->getItems(),
]);
$this->registerJs("var _opts = {$opts};");
$this->registerJs($this->render('_script.js'));
$animateIcon = ' <i class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></i>';
?>

<div class="assignment-index">

<div class="x_panel">
	<div class="x_content">
		<div class="row">
			<div class="col-sm-5">
				<input class="form-control search" data-target="available"
					placeholder="<?php echo Yii::t('rbac-admin', 'Search for available');?>">
				<select multiple size="20" class="form-control list" data-target="available"></select>
			</div>
			<div class="col-sm-2 text-center">
				<br><br>
				<?php echo Html::a('&gt;&gt;' . $animateIcon, ['assign', 'id' => (string) $model->id], [
					'class' => 'btn btn-success btn-assign',
					'data-target' => 'available',
					'title' => Yii::t('rbac-admin', 'Assign'),
				]);?>
				<br><br>
				<?php echo Html::a('&lt;&lt;' . $animateIcon, ['revoke', 'id' => (string) $model->id], [
					'class' => 'btn btn-danger btn-assign',
					'data-target' => 'assigned',
					'title' => Yii::t('rbac-admin', 'Remove'),
				]);?>
			</div>
			<div class="col-sm-5">
				<input class="form-control search" data-target="assigned"
					placeholder="<?php echo Yii::t('rbac-admin', 'Search for assigned');?>">
				<select multiple size="20" class="form-control list" data-target="assigned"></select>
			</div>
		</div>
	</div>
</div>

</div>
