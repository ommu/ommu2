<?php
namespace app\components\bootstrap;

use Yii;
use \yii\base\BootstrapInterface;
use yii\web\Cookie;
use app\models\Users;
use app\models\CoreLanguages;

class LanguageSelector implements BootstrapInterface
{
	const CACHE_ID = 'userLanguage';
    private static $_langHasLoaded = false;
    
    public function bootstrap($app)
    {
        if (self::$_langHasLoaded === false) {
            self::$_langHasLoaded = true;

            // Jika $app tidak mempunyai properti user berarti $app adalah console, exit.
            if (!$app->hasProperty('user')) {
                return;
            }

            // $language = $app->cache->get(self::CACHE_ID);
            // if ($language === false) {
                mb_internal_encoding('UTF-8');
                $isGuest = $app->user->isGuest;

                if ($isGuest) {
                    $postData = $app->request->post();
                    if (isset($postData['lang'])) {
                        $languageModel = new \app\models\form\ChooseLanguage();
                        if ($languageModel->load($app->request->post()) && $languageModel->validate()) {
                            $cookie = $this->createCookie($languageModel->language);
                            $app->getResponse()->getCookies()->add($cookie);
                            $app->language = $languageModel->language;
                        }

                    } else {
                        $allowedLanguages = $app->i18n->getAllowedLanguages();
                        $langParam = isset($app->params['defaultLanguage']) ? $app->params['defaultLanguage'] : '';

                        if ($langParam == '') {
                            $langParam = $app->language;
                        }
    
                        if (isset($app->request->cookies['language'])) {
                            $cookieLang = (string)$app->request->cookies['language'];
    
                            if (!array_key_exists($cookieLang, $allowedLanguages)) {
                                $cookie = $this->createCookie($langParam);
                                $app->getResponse()->getCookies()->add($cookie);
                            }
                        }
                        $app->language = $langParam;
                    }

                } else {
                    $postData = $app->request->post();
                    $userId = $app->user->id;
                    $userLang = $this->getUserLang($app, $userId, $postData['lang']);
                    $app->language = $userLang;
                }

                // $app->cache->set(self::CACHE_ID, $app->language);
            // }

            // $app->language = $language;
        }
    }

    protected function createCookie(?string $langId): Cookie
    {
        return new Cookie([
            'name' => 'language',
            'value' => $langId,
            'expire' => time() + 86400 * 365,
        ]);
    }

    protected function getUserLang($app, int $userId, ?string $choosedLang): ?string
    {
        $languageCode = $app->params['defaultLanguage'];
        $user = Users::find()->select(['user_id', 'language_id'])
            ->where(['user_id' => $userId])
            ->one();

        if ($user == null) {
            return $languageCode;
        }

        $userLangCode = $user->language;
        $postLang = trim($choosedLang);
        if ($postLang != '' && $postLang != $userLangCode) {
            $coreLang = CoreLanguages::find()
                ->select('language_id')
                ->where(['code'=> $postLang])
                ->one();
            $user->language_id = $coreLang->language_id;

            if ($user->save(false, ['language_id'])) {
                $languageCode = $user->language;
            }
            
        } elseif ($userLangCode) {
            $languageCode = $userLangCode;
        }

        return $languageCode;
    }
}
