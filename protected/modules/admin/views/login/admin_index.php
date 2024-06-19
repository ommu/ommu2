<?php
/**
 * @var $this yii\web\View
 * @var $this app\modules\admin\controllers\LoginController
 * @var $model app\modules\user\models\LoginForm
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 3 January 2018, 14:02 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin([
	'id' => 'login-form',
]); ?>
	<h1><?php echo Html::encode($this->title) ?></h1>

	<?php echo $form->field($model, 'username', ['template' => '{input}{error}'])
	->textInput(['autofocus' => true])
	->label(false); ?>

	<?php echo $form->field($model, 'password', ['template' => '{input}{error}'])
	->passwordInput()
	->label(false); ?>

	<div>
		<?php echo Html::submitButton(Yii::t('app', 'Login'), ['class' => 'btn btn-default submit mt-10 ml-15',
			'name' => 'login-button']) ?>
		<?php echo $form->field($model, 'rememberMe', ['options' => ['class' => 'reset_pass']])->checkbox([
			'template' => "{input} {label}",
		]) ?>
	</div>

	<div class="clearfix"></div>
	<div class="separator">
		<p class="change_link">New to site?
			<?php echo Html::a(Yii::t('app', 'Lost your password?'), Url::to(['/user/password/forgot']), ['class' => 'to_register'])?>
		</p>
		<div class="clearfix"></div>
		<br />
		<div>
			<h1><i class="fa fa-paw"></i> Gentelella Alela!</h1>
			<p>©2016 All Rights Reserved. Gentelella Alela! is a Bootstrap 3 template. Privacy and Terms</p>
		</div>
	</div>

<?php ActiveForm::end(); ?>
module