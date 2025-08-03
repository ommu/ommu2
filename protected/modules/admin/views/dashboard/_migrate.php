<?php
/**
 * @var $this yii\web\View
 * @var $this app\modules\admin\controllers\DashboardController
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 3 January 2018, 00:24 WIB
 * @link https://github.com/ommu/ommu2
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php echo Html::a(Yii::t('app', 'Migrate Up'), Url::to(['/admin/migrate/set']), ['class' => 'btn btn-success modal-btn', 'data-target'=> 'modalBroadcast'])?>