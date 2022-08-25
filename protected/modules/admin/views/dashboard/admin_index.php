<?php
/**
 * @var $this yii\web\View
 * @var $this app\modules\admin\controllers\DashboardController
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 3 January 2018, 00:24 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Dashboard'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Summary');

\app\assets\CentrifugeAsset::register($this);
$js = <<<JS
    const container = document.getElementById('counter');

    function getToken(url, ctx) {
        return new Promise((resolve, reject) => {
            fetch(url, {
                method: 'POST',
                headers: new Headers({ 'Content-Type': 'application/json' }),
                body: JSON.stringify(ctx)
            })
            .then(res => {
                if (!res.ok) {
                    throw new Error('Unexpected status code ' + res.status);
                }
                return res.json();
            })
            .then(data => {
                resolve(data.token);
            })
            .catch(err => {
                reject(err);
            });
        });
    }

    const centrifuge = new Centrifuge('ws://localhost:8000/connection/websocket', {
        token: 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM3MjIiLCJleHAiOjE2NjIwMzQ5MjEsImlhdCI6MTY2MTQzMDEyMX0.lT3Iw8AqCI6OWMdRWSmFagulGAnhcUTvIJw--8BTq1E'
        // token: 'JWT-GENERATED-ON-BACKEND-SIDE'
        // getToken: function (ctx) {
        //     return getToken('/centrifuge/connection_token', ctx);
        // }
    });

    centrifuge.on('connecting', function (ctx) {
        console.log('connecting: ' + ctx.code + ', ' + ctx.reason);
    }).on('connected ', function (ctx) {
         console.log('connected over' + ctx.transport);
    }).on('disconnected ', function (ctx) {
        console.log('disconnected:' + ctx.code+ ', ' +ctx.reason);
    }).connect();

    const sub = centrifuge.newSubscription("devtool");

    sub.on('publication', function (ctx) {
        $('#modalBroadcast').find('.log-content pre.preformat').append(ctx.data.message);
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

<div id="counter">-</div>

<?php echo $this->renderWidget('admin_development', [
    'title' => Yii::t('app', 'Development Tool'),
	'contentMenu' => true,
	'breadcrumb' => false,
]); ?>