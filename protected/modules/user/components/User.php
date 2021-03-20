<?php
/**
 * User klas
 *
 * Klas ini untuk override identity yii (Yii::$app->user->)
 * - afterLogin, refresh token jika sudah expire
 * - beforeLogout, clear cache menu
 * - loginByAccessToken, overrided untuk api JWT
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 28 Mei 2017, 06:30 WIB
 * @link https://github.com/ommu/ommu
 * 
 */

namespace app\modules\user\components;

use Yii;
use app\modules\user\models\Users;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Predis\Client;
use yii\helpers\Json;

class User extends \yii\web\User
{
	use \app\modules\user\components\traits\UserTrait;

	/**
	 * Menyimpan id untuk authentikasi dengan JWT
	 */
	const JWT_NOT_BEFORE         = 60;
	const JWT_TOKEN_EXPIRE       = 3600 * 24 * 7;
	const EVENT_INVALIDATE_CACHE = 'invalidateCache';

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		parent::init();
	}

	/**
	 * afterLogin
	 * 
	 * Generate web token setelah user login
	 */
	protected function afterLogin($identity, $cookieBased, $duration)
	{
		$this->generateJwtTokenIfExpire($identity);

		parent::afterLogin($identity, $cookieBased, $duration);
	}

	/**
	 * beforeLogout
	 * 
	 * Bersihkan cache menu sebelum user logout
	 */
	protected function beforeLogout($identity)
	{
		if (parent::beforeLogout($identity)) {
			$this->trigger(self::EVENT_INVALIDATE_CACHE);
		}

		return true;
	}

	/**
	 * Generate jwt jika yang sekarang sudah expire
	 * 
	 * @see afterLogin()
	 */
	private function generateJwtTokenIfExpire($identity)
	{
		$jwtClaims = '';
		if ($identity == null) {
			return;
        }

		if ($identity->jwt_claims == null || $identity->jwt_claims == '') {
			$identity->refreshToken($identity->getId());
			return;
		}

		$userId = $identity->getId();
		$user   = Users::find()
			->select(['user_id', 'jwt_claims'])
			->where(['user_id' => $userId])
			->one();
		if ($user == null) {
			return;
        }

		$claims = unserialize($user->jwt_claims);
		if (is_array($claims) === false) {
			throw new \Exception('Cannot verify claim!.');
        }

		$token = (string) $this->buildJwtTokenFromClaim($claims, $userId);
		if ($token == null || trim($token) == '' || substr_count($token, '.') < 2) {
			$identity->refreshToken($userId);
			return;
		}

		$tokenObject = Yii::$app->jwt->getParser()->parse((string) $token);
		if (!Yii::$app->jwt->validateToken($tokenObject)) {
			$identity->refreshToken($userId);
        }
	}

	/**
	 * {@inheritdoc}
	 */
	public function getLanguage()
	{
		if ($this->isGuest) {
			return Yii::$app->params['defaultLanguage'] ?? 'id';
        }

		return $this->getIdentity()->language;
	}

	/**
	 * {@inheritdoc}
	 */
	public function loginByAccessToken($token, $type=\yii\filters\auth\HttpBearerAuth)
	{
		if (trim($token) == '') {
			return null;
        }

		$userToken = '';
		if ($token instanceof \Lcobucci\JWT\Token) {
			$userToken = $token;
        } else {
			$userToken = Yii::$app->jwt->getParser()->parse((string) $token);
        }

		if (Yii::$app->jwt->validateToken($userToken)) {
			$uid = $userToken->getClaim('uid');
			$identity = Users::find()
				->select('user_id, username, email, auth_key')
				->where(['user_id' => $uid])
				->one();

			return $identity;
		}

		return null;
	}
}
