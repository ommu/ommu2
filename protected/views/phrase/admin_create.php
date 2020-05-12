<?php
/**
 * Source Messages (source-message)
 * @var $this app\components\View
 * @var $this app\controllers\PhraseController
 * @var $model app\models\SourceMessage
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 6 December 2019, 10:32 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Development Tools'), 'url' => ['admin/module/manage']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Phrase'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="source-message-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
