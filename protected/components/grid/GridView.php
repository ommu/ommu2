<?php
/**
 * GridView for OMMU
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 2 May 2019, 09:39 WIB
 * @link https://github.com/ommu/ommu
 */

namespace app\components\grid;

use Yii;

class GridView extends \yii\grid\GridView
{
    /**
     * {@inheritdoc}
     */
    public static function widget($config = [])
    {
        $view = Yii::$app->view;

        if (isset($view->themeSetting['widget_class']['GridView'])) {
            $themeParentClass = $view->themeSetting['widget_class']['GridView'];

            return $themeParentClass::widget($config);
        }

        return parent::widget($config);
    }
}
