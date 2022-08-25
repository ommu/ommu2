<?php
/**
 * @var $this app\components\View
 * @var $this app\modules\admin\controllers\MigrateController
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 25 August 2022, 13:23 WIB
 * @link https://www.ommu.id
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="alert alert-danger hide" role="alert"></div>

<?php echo Html::beginForm(['set'], 'POST', ['class' => 'form-horizontal form-label-left']);?>

<div class="form-group row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <?php echo Html::dropDownList('modulePath', '', $modules, ['prompt' => '', 'class' => 'form-control mb-5']);?>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <?php echo Html::submitButton(Yii::t('app', 'Run..'), ['class'=>'btn btn-primary']);?>
    </div>
</div>

<?php echo Html::endForm(); ?>

<div class="log-content">
    <hr/>
    <pre class="preformat"></pre>
</div>