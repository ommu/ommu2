<?php
/**
 * @var $this yii\web\View
 * @var $this app\modules\admin\controllers\DashboardController
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 3 January 2018, 00:24 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;

\app\assets\CentrifugeAsset::register($this);
$js = <<<JS
    const sub = centrifuge.newSubscription('devtool');

    sub.on('publication', function (ctx) {
        let el = $('#modalBroadcast');
        el.find('.log-content pre.preformat').html('');
        el.find('.log-content pre.preformat').append(ctx.data.message);
    }).on('subscribing', function (ctx) {
        console.log('subscribing: ' + ctx.code + ', ' + ctx.reason);
    }).on('subscribed ', function (ctx) {
        console.log('subscribed ', ctx);
    }).on('unsubscribed', function (ctx) {
        console.log('unsubscribed: ' + ctx.code + ', ' + ctx.reason);
    }).subscribe();
JS;
$this->registerJs($js, $this::POS_END);
?>

<?php echo Html::a(Yii::t('app', 'Composer'), Url::to(['/admin/composer/set']), ['class' => 'btn btn-success modal-btn', 'data-target'=> 'modalBroadcast'])?>