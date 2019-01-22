<?php
/**
 * Modules (modules)
 * @var $this yii\web\View
 * @var $this app\modules\admin\controllers\ModuleController
 * @var $model app\modules\admin\models\search\Modules
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 26 December 2017, 09:41 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\helpers\Html;
use app\components\ActiveForm;
?>

<div class="modules-search search-form">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => [
			'data-pjax' => 1
		],
	]); ?>

		<?php echo $form->field($model, 'module_id');?>

		<?php echo $form->field($model, 'creation_date')
			->input('date');?>

		<?php echo $form->field($model, 'creation_search');?>

		<?php echo $form->field($model, 'modified_date')
			->input('date');?>

		<?php echo $form->field($model, 'modified_search');?>

		<?php echo $form->field($model, 'installed')
			->dropDownList($this->filterYesNo(), ['prompt'=>'']);?>

		<?php echo $form->field($model, 'enabled')
			->dropDownList($this->filterYesNo(), ['prompt'=>'']);?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>