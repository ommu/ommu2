<?php
/**
 * @var $this DefaultController
 * @var $this yii\web\View
 * @var $generator yii\gii\Generator
 * @var $id string panel ID
 * @var $form yii\widgets\ActiveForm
 * @var $results string
 * @var $hasError bool
 * @var $files CodeFile[]
 * @var $answers array
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 24 September 2017, 12:38 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\helpers\Html;
use app\components\ActiveForm;
use yii\gii\components\ActiveField;
use yii\gii\CodeFile;

$templates = [];
foreach ($generator->templates as $name => $path) {
    $templates[$name] = "$name ($path)";
}
?>

<div class="default-view">
	<div class="x_panel">
		<div class="x_content">
			<?php $form = ActiveForm::begin([
				'id' => "$id-generator",
				'successCssClass' => '',
				'fieldConfig' => ['class' => ActiveField::className()],
			]); ?>

			<?php echo $this->renderFile($generator->formView(), [
				'generator' => $generator,
				'form' => $form,
			]); ?>

			<?php echo $form->field($generator, 'template', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}</div>{hint}'])->sticky()
				->label('Code Template', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12'])
				->dropDownList($templates)->hint('Please select which set of the templates should be used to generated the code.') ?>

			<div class="ln_solid"></div>
			<div class="form-group">
				<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
					<?php echo Html::submitButton('Preview', ['name' => 'preview', 'class' => 'btn btn-primary']) ?>
					<?php if (isset($files)): ?>
						<?php echo Html::submitButton('Generate', ['name' => 'generate', 'class' => 'btn btn-success']) ?>
					<?php endif; ?>
				</div>
			</div>

			<?php if (isset($results) || isset($files)) {?>
			<div class="ln_solid"></div>
			<div class="default-view">
				<div class="x_panel">
					<div class="x_content">
						<?php if (isset($results)) {
							echo $this->render('@yii/gii/views/default/view/results', [
								'generator' => $generator,
								'results' => $results,
								'hasError' => $hasError,
							]);
						} elseif (isset($files)) {
							echo $this->render('@yii/gii/views/default/view/files', [
								'id' => $id,
								'generator' => $generator,
								'files' => $files,
								'answers' => $answers,
							]);
						}?>
					</div>
				</div>
			</div>
			<?php }?>

			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
