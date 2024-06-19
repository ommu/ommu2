<?php
/**
 * @var $this app\components\View
 * @var $this app\modules\admin\controllers\SymlinkController
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 24 August 2022, 09:14 WIB
 * @link https://www.ommu.id
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="alert alert-danger hide" role="alert"></div>

<?php echo Html::beginForm(['set'], 'POST', ['class' => 'form-horizontal form-label-left']);?>

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