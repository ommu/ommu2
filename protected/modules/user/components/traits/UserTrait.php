<?php
/**
 * UserTrait
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 10 December 2017, 14:03 WIB
 * @link https://github.com/ommu/ommu2
 *
 * Contains many function that most used :
 *	buildJwtTokenFromClaim
 *
 */

declare(strict_types=1);

namespace app\modules\user\components\traits;

use Yii;
use Lcobucci\JWT\Signer\Hmac\Sha256;

trait UserTrait
{
	/**
	 * @param array $claim data klaim yang akan digunakan untuk mengenerate token
	 * @param int $uid user id
	 * @return string token jwt
	 */
	public function buildJwtTokenFromClaim(array $claims, int $uid): object
	{
		$signer = new Sha256();
		$token = Yii::$app->jwt->getBuilder()
			->setSubject($claims['sub'])
			->setIssuer($claims['issuer'])
			->setAudience($claims['aud'])
			->setId($claims['id'], true)
			->setIssuedAt($claims['issued_at'])
			->setNotBefore($claims['nbf'])
			->setExpiration($claims['exp'])
			->set('uid', $uid)
			->set('email', $claims['sub'])
			->sign($signer, Yii::$app->jwt->key)
			->getToken();

		return $token;
	}
}