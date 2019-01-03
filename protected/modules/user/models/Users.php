<?php
/**
 * Users
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 16 November 2017, 14:42 WIB
 * @link https://github.com/ommu/ommu
 *
 */

namespace app\modules\user\models;

use Yii;
use yii\web\IdentityInterface;
use ommu\users\models\Users as UsersModel;
use ommu\users\models\UserLevel;
use app\modules\user\components\User as UserIdentity;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class Users extends UsersModel implements IdentityInterface
{
	const STATUS_ACTIVE = 1;
	const STATUS_BLOCK = 2;

	/**
	 * Finds an identity by the given ID.
	 *
	 * @param string|int $id the ID to be looked for
	 * @return IdentityInterface|null the identity object that matches the given ID.
	 */
	public static function findIdentity($id)
	{
		return static::findOne($id);
	}

	/**
	 * Finds an identity by the given token.
	 *
	 * @param string $token the token to be looked for
	 * @return IdentityInterface|null the identity object that matches the given token.
	 */
	public static function findIdentityByAccessToken($token, $type = null)
	{
		// return static::findOne(['access_token' => $token]);
		return static::findOne(['auth_key' => $token]);
	}

	/**
	 * @return int|string current user ID
	 */
	public function getId()
	{
		return $this->getPrimaryKey();
	}

	/**
	 * @return string current user auth key
	 */
	public function getAuthKey()
	{
		return $this->auth_key;
	}

	/**
	 * @param string $authKey
	 * @return bool if auth key is valid for current user
	 */
	public function validateAuthKey($authKey)
	{
		return $this->getAuthKey() === $authKey;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		$rules = parent::rules();

		return \yii\helpers\ArrayHelper::merge($rules, [
		]);
	}

	/**
	 * Finds user by email
	 *
	 * @param string $email
	 * @return static|null
	 */
	public function findByEmail($email, $isAdmin=false)
	{
		if($isAdmin == true) {
			$level = UserLevel::getLevel('admin');
			return static::find()->alias('t')
				->where(['t.email' => $email])
				->andWhere(['t.enabled' => self::STATUS_ACTIVE])
				->andWhere(['in', 't.level_id', array_flip($level)])->one();
		} else
			return static::findOne(['email' => $email, 'enabled' => self::STATUS_ACTIVE]);
	}

	/**
	 * Validates password
	 *
	 * @param  string  $password password to validate
	 * @return boolean if password provided is valid for current user
	 */
	public function validatePassword($password)
	{
		if($this->scenario == Users::SCENARIO_CHANGE_PASSWORD) {
			if(!Yii::$app->security->validatePassword($this->$password, $this->password_i)) {
				$this->addError($password, Yii::t('app', '{attribute} is incorrect.', [
					'attribute'=>$this->getAttributeLabel($password),
				]));
			}
		} else
			return Yii::$app->security->validatePassword($password, $this->password);
	}

	/**
	 * Refresh user token
	 *
	 * @param integer $id user id
	 * @return string token baru
	 */
	public function refreshToken(int $id): string
	{
		$user = self::find()->where(['user_id' => $id])->select('user_id, email')->one();
		if($user == null)
			throw new \Exception('Tidak dapat merefresh token!.');

		// yang perlu disimpan ke database agar menghemat space ruang
		// issuer, subject(di isi username saja), audience, expiration, not before, issued at, jwt id
		$issuedAt = time();
		$nbf = time() + UserIdentity::JWT_NOT_BEFORE;
		$exp = time() + UserIdentity::JWT_TOKEN_EXPIRE;
		$signer = new Sha256();
		$token = Yii::$app->jwt->getBuilder()
			->setSubject($user->email)
			->setIssuer(UserIdentity::JWT_ISSUER)
			->setAudience(UserIdentity::JWT_AUDIENCE)
			->setId(UserIdentity::JWT_UNIQ_ID, true)
			->setIssuedAt($issuedAt)
			->setNotBefore($nbf)
			->setExpiration($exp)
			->set('uid', $id)
			->set('email', $user->email)
			->sign($signer, Yii::$app->jwt->key)
			->getToken();

		// Simpan jwt claim ke database!
		$jwtClaims = [
			'issuer'    => UserIdentity::JWT_ISSUER,
			'aud'       => UserIdentity::JWT_AUDIENCE,
			'id'        => UserIdentity::JWT_UNIQ_ID,
			'issued_at' => $issuedAt,
			'nbf'       => $nbf,
			'exp'       => $exp,
			'sub'       => $user->email,
			'user_id'   => $id,
		];
		$claim = serialize($jwtClaims);

		self::getDb()
			->createCommand()
			->update(self::tableName(), ['jwt_claims' => $claim], 'user_id= :uid', [':uid' => $id])
			->execute();

		return (string)$token;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function getClaimById($userId)
	{
		$result = self::find()->where(['user_id' => $userId])->select(['user_id', 'jwt_claims'])->one();
		if($result != null)
			return $result->jwt_claims;

		return '';
	}
}
