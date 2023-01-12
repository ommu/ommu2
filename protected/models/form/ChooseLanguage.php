<?php
namespace app\models\form;

use Yii;

class ChooseLanguage extends \yii\base\Model
{
    public $language;

    public function rules()
    {
        return [
            ['language', 'in', 'range' => array_keys(Yii::$app->i18n->getAllowedLanguages())],
        ];
    }

    public function attributeLabels()
    {
        return [
            'language' => Yii::t('app', 'Language'),
        ];
    }
}