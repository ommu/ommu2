<?php
/**
 * @var $this yii\web\View
 * @var $routes []
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 28 December 2017, 06:50 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use mdm\admin\AnimateAsset;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\YiiAsset;

$this->context->layout = 'assignment';
$this->title = Yii::t('rbac-admin', 'Routes');
$this->params['breadcrumbs'][] = $this->title;

AnimateAsset::register($this);
YiiAsset::register($this);
$opts = Json::htmlEncode([
	'routes' => $routes,
]);
$this->registerJs("var _opts = {$opts};");
$this->registerJs($this->render('_script.js'));
$animateIcon = ' <i class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></i>';
?>

<div class="row">
	<div class="col-sm-8 col-sm-offset-2 pt-20 pb-20">
		<div class="input-group">
			<input id="inp-route" type="text" class="form-control"
				placeholder="<?php echo Yii::t('rbac-admin', 'New route(s)');?>">
			<span class="input-group-btn">
				<?php echo Html::a(Yii::t('rbac-admin', 'Add') . $animateIcon, ['create'], [
					'class' => 'btn btn-primary',
					'id' => 'btn-new',
				]);?>
			</span>
		</div>
	</div>
</div>

<div class="x_panel">
	<div class="x_content">
		<div class="row">
			<div class="col-sm-5">
				<div class="input-group">
					<input class="form-control search" data-target="available"
						placeholder="<?php echo Yii::t('rbac-admin', 'Search for available');?>">
					<span class="input-group-btn">
						<?php echo Html::a('<span class="glyphicon glyphicon-refresh"></span>', ['refresh'], [
							'class' => 'btn btn-default',
							'id' => 'btn-refresh',
						]);?>
					</span>
				</div>
				<select multiple size="20" class="form-control list" data-target="available"></select>
			</div>
			<div class="col-sm-2 text-center">
				<br><br>
				<?php echo Html::a('&gt;&gt;' . $animateIcon, ['assign'], [
					'class' => 'btn btn-success btn-assign',
					'data-target' => 'available',
					'title' => Yii::t('rbac-admin', 'Assign'),
				]);?>
				<br><br>
				<?php echo Html::a('&lt;&lt;' . $animateIcon, ['remove'], [
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