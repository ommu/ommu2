<?php
namespace app\components\widgets;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class Breadcrumbs extends \yii\widgets\Breadcrumbs
{
    /**
     * {@inheritdoc}
     */
    public $itemTemplate = "<li>{link}{separator}</li>\n";
    /**
    * @var string the separator between links in the breadcrumbs. Defaults to ' <span>\</span> '.
    */
    public $separator;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        // set default navigationOptions
        if(!isset($this->options['class']))
            $this->options['class'] = 'breadcrumb';

        if(!isset($this->options['link']))
            $this->options['link'] = [];

        if(!isset($this->options['separator']))
            $this->options['separator'] = [];
            
        if(!isset($this->separator))
            $this->separator = $this->renderSeparator();

    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $options = $this->options;
        ArrayHelper::remove($options, 'link');
        ArrayHelper::remove($options, 'separator');

        parent::run();
    }

    /**
     * {@inheritdoc}
     */
    protected function renderSeparator()
    {
        $options = $this->options;

        ArrayHelper::remove($options['separator'], 'id');
        $tag = ArrayHelper::remove($options['separator'], 'tag', 'span');
        $label = (isset($options['separator']['label']) ? $options['separator']['label'] : '\\');
        ArrayHelper::remove($options['separator'], 'label');
        return Html::tag($tag,  $label, $options['separator']);
    }

    /**
     * {@inheritdoc}
     */
     protected function renderItem($link, $template)
    {
        if(!isset($link['class']))
            $link = ArrayHelper::merge($link, $this->options['link']);
        $parent = parent::renderItem($link, $template);

        return strtr($parent, ['{separator}' => $this->separator]);
    }
}