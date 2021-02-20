<?php
/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */

namespace app\modules\user\models;

use Yii;
use yii\base\Model;
use app\models\CoreSettings;

class LoginForm extends Model
{
	public $username;
	public $password;
	public $rememberMe = false;
	public $isAdmin = false;
	public $is_api;

	private $_user = false;
	private $_byEmail = false;

	/**
	 * (@inheritdoc)
	 */
	protected $_labels;

	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		$rules = [
			// username and password are both required
			[['username', 'password'], 'required'],
			// rememberMe must be a boolean value
			[['rememberMe', 'is_api'], 'boolean'],
			// password is validated by validatePassword()
			['password', 'validatePassword'],
			[['is_api'], 'safe'],
		];

		return $rules;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'username' => Yii::t('app', 'Email'),
			'password' => Yii::t('app', 'Password'),
			'rememberMe' => Yii::t('app', 'Remember'),
		];
	}

	/**
	 * (@inheritdoc)
	 */
	public function setAttributeLabels($labels)
	{
		$this->_labels = $labels;
	}

	/**
	 * (@inheritdoc)
	 */
	public function getAttributeLabel($attribute)
	{
		return $this->_labels[$attribute] ?? parent::getAttributeLabel($attribute);
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
			if(!$this->hasErrors()) {
				$user = $this->getUser($this->isAdmin);
				if(!$user)
					$this->addError('username', Yii::t('app', '{attribute} is incorrect.', ['attribute' => $this->getAttributeLabel('username')]));
			}
		}
		return true;
	}

	/**
	 * Validates the password.
	 * This method serves as the inline validation for password.
	 *
	 * @param string $attribute the attribute currently being validated
	 * @param array $params the additional name-value pairs given in the rule
	 */
	public function validatePassword($attribute, $params)
	{
		if (!$this->hasErrors()) {
			$user = $this->getUser($this->isAdmin);

			if($user) {
				if($user->oldSecurity == false) {
					if(!$user->validatePassword($this->password))
						$this->addError($attribute, Yii::t('app', 'Password is incorrect.'));
				} else {
					if(!$user->hashPassword($this->password))
						$this->addError($attribute, Yii::t('app', 'Password is incorrect.'));
				}
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function setByEmail(bool $val): void 
	{
		$this->_byEmail = $val;
	}

	/**
	 * Finds user by [[username]]
	 *
	 * @return User|null
	 */
	public function getUser($isAdmin=false)
	{
		if(Yii::$app->isSocialMedia()) {
			$setting = CoreSettings::find()
				->select(['signup_username'])
				->where(['id' => 1])
				->one();

			if($setting->signup_username == 1 && $this->_user === false && $this->_byEmail === false)
				$this->_user = Users::findByUsername($this->username, $isAdmin);
	
			elseif($this->_user === false && $this->_byEmail === true)
				$this->_user = Users::findByEmail($this->username, $isAdmin);
		} else
			$this->_user = Users::findByEmail($this->username, $isAdmin);

		return $this->_user;
	}

	/**
	 * Logs in a user using the provided username and password.
	 * @return boolean whether the user is logged in successfully
	 */
	public function login()
	{
		if($this->validate()) {
			$user = $this->getUser($this->isAdmin);
			if(!$this->is_api) {
				Yii::$app->session->set('_backend_app', true);
				Yii::$app->session->set('__name', $user->username);
				Yii::$app->session->set('__states', serialize([
					'id' => $user->user_id,
					'__id' => $user->user_id,
					'_backend_app' => true,
				]));
				$user->lastlogin_from = 'web';
			} else
				$user->lastlogin_from = 'api';
			$user->lastlogin_date = Yii::$app->formatter->asDate('now', 'php:Y-m-d H:i:s');
			$user->lastlogin_ip = $_SERVER['REMOTE_ADDR'];
			$user->save(false, ['lastlogin_date','lastlogin_ip','lastlogin_from']);

			return Yii::$app->user->login($this->getUser($this->isAdmin), $this->rememberMe ? 3600*24*7 : 0);
		}

		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributes()
	{
		return array_merge(parent::attributes(), ['rememberMe', 'isAdmin', 'is_api']);
	}
}
