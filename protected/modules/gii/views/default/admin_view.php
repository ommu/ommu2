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
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 24 September 2017, 12:38 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use app\components\widgets\GiiActiveField as ActiveField;
use yii\gii\CodeFile;

$templates = [];
foreach ($generator->templates as $name => $path) {
    $templates[$name] = "$name ($path)";
}
?>

<div class="default-view">
	<?php $form = ActiveForm::begin([
		'options' => ['class' => 'form-horizontal form-label-left'],
		'id' => "$id-generator",
		'successCssClass' => '',
		'fieldConfig' => [
			'class' => ActiveField::className(),
		],
	]); ?>

	<?php echo $this->renderFile($generator->formView(), [
		'generator' => $generator,
		'form' => $form,
	]); ?>

	<?php echo $form->field($generator, 'template', ['template' => '{label}{beginWrapper}{input}{error}{endWrapper}{hint}'])
		->sticky()
		->label('Code Template')
		->dropDownList($templates)->hint('Please select which set of the templates should be used to generated the code.') ?>

	<hr/>

	<?php $generateButton = isset($files) ? Html::submitButton('Generate', ['name' => 'generate', 'class' => 'btn btn-success']) : '';
	echo $form->field($generator, 'submitButton', ['template' => '{beginWrapper}{input}'.$generateButton.'{endWrapper}'])
		->submitButton(['button' => Html::submitButton('Preview', ['name' => 'preview', 'class' => 'btn btn-info'])]); ?>

	<?php if (isset($results) || isset($files)) {?>
	<hr/>
	<div class="default-view">
		<div class="x_panel">
			<div class="x_content">
				<?php if (isset($results)) {
					echo $this->render('@yii/gii/views/default/view/results', [
						'generator' => $generator,
						'results' => $results,
						'hasError' => $hasError,
					]);
				} else if (isset($files)) {
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