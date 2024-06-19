<?php
/**
 * SerialColumn for OMMU
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2020 OMMU (www.ommu.id)
 * @created date 18 Fabruary 2020, 20:52 WIB
 * @link https://github.com/ommu/ommu
 */

namespace app\components\grid;

use Yii;
use yii\helpers\Html;

function get_serial_column_parent() {
    if (isset(Yii::$app->view->themeSetting['widget_class']['SerialColumn'])) {
        return Yii::$app->view->themeSetting['widget_class']['SerialColumn'];
    }

    return 'yii\grid\SerialColumn';
}
class_alias(get_serial_column_parent(), 'app\components\grid\OSerialColumn');

class SerialColumn extends OSerialColumn
{

}
