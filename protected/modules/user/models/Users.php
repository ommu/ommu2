<?php
/**
 * Users
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
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
use kartik\password\StrengthValidator;

class Users extends UsersModel implements IdentityInterface
{
	const STATUS_ACTIVE = 1;
	const STATUS_BLOCK = 2;

	/**
	 * {@inheritdoc}
	 */
	public $photos;
	public $oldSecurity = false;

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

		if(isset(Yii::$app->params['user']['enableStrengthValidator']) && Yii::$app->params['user']['enableStrengthValidator'] == true) {
			return \yii\helpers\ArrayHelper::merge($rules, [
				[['password'], StrengthValidator::className(), 'preset' => StrengthValidator::FAIR],
			]);
		}

		return $rules;
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->photos = join('/', [self::getUploadPath(false), 'default.png']);
		$this->oldSecurity = strlen($this->password) == 60 ? false : true;
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
			return static::find()
				->where(['enabled' => self::STATUS_ACTIVE])
				->andWhere(['in', 'level_id', array_flip($level)])
				->andWhere(['email' => $email])
				->one();
		}

		return static::findOne(['enabled' => self::STATUS_ACTIVE, 'email' => $email]);
	}

	/**
	 * Finds user by username
	 *
	 * @param string $username
	 * @return static|null
	 */
	public function findByUsername($username, $isAdmin=false)
	{
		if($isAdmin == true) {
			$level = UserLevel::getLevel('admin');
			return static::find()
				->where(['enabled' => self::STATUS_ACTIVE])
				->andWhere(['in', 'level_id', array_flip($level)])
				->andWhere(['username' => $username])
				->one();
		}

		return static::findOne(['enabled' => self::STATUS_ACTIVE, 'username' => $username]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function hashPassword($password)
	{
		return md5($this->salt.$password) === $this->password_i;
	}

	/**
	 * Validates password
	 *
	 * @param  string  $password password to validate
	 * @return boolean if password provided is valid for current user
	 */
	public function validatePassword($password): bool
	{
		if($this->scenario == Users::SCENARIO_CHANGE_PASSWORD) {
			if($this->oldSecurity == false) {
				if(!Yii::$app->security->validatePassword($this->$password, $this->password_i)) {
					$this->addError($password, Yii::t('app', '{attribute} is incorrect.', [
						'attribute' => $this->getAttributeLabel($password),
					]));
					return false;
				}
				return true;
			} else {
				if(!$this->hashPassword($this->$password)) {
					$this->addError($password, Yii::t('app', '{attribute} is incorrect.', [
						'attribute' => $this->getAttributeLabel($password),
					]));
					return false;
				}
				return true;
			}
		}
		
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
		$user = self::find()
			->select('user_id, email')
			->where(['user_id' => $id])
			->one();

		if($user == null)
			throw new \Exception('Tidak dapat merefresh token!.');

		// yang perlu disimpan ke database agar menghemat space ruang
		// subject(di isi email saja), issuer, audience, issued at, not before, expiration, jwt id
		$issuedAt = time();
		$nbf = time() + UserIdentity::JWT_NOT_BEFORE;
		$exp = time() + UserIdentity::JWT_TOKEN_EXPIRE;
		$signer = new Sha256();
		$token = Yii::$app->jwt->getBuilder()
			->setSubject($user->email)
			->setIssuer(Yii::$app->jwt->issuer)
			->setAudience(Yii::$app->jwt->audiance)
			->setId(Yii::$app->jwt->id, true)
			->setIssuedAt($issuedAt)
			->setNotBefore($nbf)
			->setExpiration($exp)
			->set('uid', $id)
			->set('email', $user->email)
			->sign($signer, Yii::$app->jwt->key)
			->getToken();

		// Simpan jwt claim ke database!
		$jwtClaims = [
			'sub'       => $user->email,
			'issuer'    => Yii::$app->jwt->issuer,
			'aud'       => Yii::$app->jwt->audiance,
			'id'        => Yii::$app->jwt->id,
			'issued_at' => $issuedAt,
			'nbf'       => $nbf,
			'exp'       => $exp,
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
	 * Mengembalikan data pada kolom jwt_claims untuk kebutuhan autentifikasi
	 */
	public static function getClaimById(int $userId)
	{
		$result = self::find()
			->where(['user_id' => $userId])
			->select(['user_id', 'jwt_claims'])
			->one();

		if($result != null)
			return $result->jwt_claims;

		return '';
	}
}
