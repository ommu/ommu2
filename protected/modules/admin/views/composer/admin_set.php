<?php
/**
 * @var $this app\components\View
 * @var $this app\modules\admin\controllers\ComposerController
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 25 August 2022, 09:14 WIB
 * @link https://www.ommu.id
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="alert alert-danger hide" role="alert"></div>

<?php echo Html::beginForm(['set'], 'POST', ['class' => 'form-horizontal form-label-left']);?>

<div class="form-group row">
    <div class="col-md-4 col-sm-4 col-xs-12">
        <?php echo Html::dropDownList('args[key1]', '', ['install' => 'install', 'update' => 'update'], ['prompt' => '', 'class' => 'form-control mb-5']);?>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-12">
        <?php echo Html::dropDownList('args[key2]', '', ['--ignore-platform-reqs' => '--ignore-platform-reqs'], ['prompt' => '', 'class' => 'form-control mb-5']);?>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-12">
        <?php echo Html::dropDownList('args[key3]', '', ['-v' => 'normal output', '-vv' => 'more verbose output', '-vvv' => 'debug'], ['prompt' => '', 'class' => 'form-control mb-5']);?>
    </div>
</div>

<div class="form-group row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <?php echo Html::submitButton(Yii::t('app', 'Run..'), ['class'=>'btn btn-primary']);?>
    </div>
</div>

<?php echo Html::endForm(); ?>

<div class="log-content">
    <hr/>
    <pre class="preformat"></pre>
</div>