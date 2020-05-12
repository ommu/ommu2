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
    public $theadCssClass;
    /**
     * {@inheritdoc}
     */
    public $tbodyCssClass;

    /**
     * {@inheritdoc}
     */
    public function renderTableHeader()
    {
        if(isset($this->theadCssClass)) {
            $parent = parent::renderTableHeader();
            return strtr($parent, ['<thead>' => '<thead class="'.$this->theadCssClass.'">']);
        }

        return parent::renderTableHeader();
    }

    /**
     * {@inheritdoc}
     */
    public function renderTableBody()
    {
        if(isset($this->tbodyCssClass)) {
            $parent = parent::renderTableBody();
            return strtr($parent, ['<tbody>' => '<tbody class="kt-'.$this->tbodyCssClass.'">']);
        }

        return parent::renderTableBody();
    }

    /**
     * {@inheritdoc}
     */
    public static function widget($config = [])
    {
        $parentClass = get_parent_class();
        if(isset(Yii::$app->view->themeSetting['widget_class']['GridView']))
            $parentClass = Yii::$app->view->themeSetting['widget_class']['GridView'];

        return $parentClass::widget($config);
    }
}
