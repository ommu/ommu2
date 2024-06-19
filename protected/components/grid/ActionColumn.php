<?php
/**
 * ActionColumn for OMMU
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 3 May 2019, 13:22 WIB
 * @modified date 18 February 2020, 17:37 WIB
 * @link https://github.com/ommu/ommu
 */

namespace app\components\grid;

use Yii;
use yii\helpers\Html;

function get_active_column_parent() {
    if (isset(Yii::$app->view->themeSetting['widget_class']['ActionColumn'])) {
        return Yii::$app->view->themeSetting['widget_class']['ActionColumn'];
    }

    return 'yii\grid\ActionColumn';
}
class_alias(get_active_column_parent(), 'app\components\grid\OActionColumn');

class ActionColumn extends OActionColumn
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if (!isset($this->contentOptions['class'])) {
            Html::addCssClass($this->contentOptions, 'action-column');
        }
    }
}
