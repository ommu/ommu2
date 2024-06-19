<?php
/**
 * Jwt class
 * Turunan klas dari \size\jwt\Jwt dengan tambahan validasi token yang sesuai dengan kebutuhan aplikasi
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 4 January 2018, 11:29 WIB
 * @link https://github.com/ommu/ommu
 * 
 */

declare(strict_types=1);

namespace app\components;

use app\modules\user\components\User;
use Lcobucci\JWT\Token;

class Jwt extends \sizeg\jwt\Jwt
{
	/**
	 * {@inheritdoc}
	 */
	public $issuer;
	/**
	 * {@inheritdoc}
	 */
	public $audiance;
	/**
	 * {@inheritdoc}
	 */
	public $id;
	
	/**
	 * Mengganti fungsi validasi token disesuaikan dg kebutuhan aplikasi
	 * example: Yii::$app->jwt->validateToken
	 */
	public function validateToken(Token $token, $currentTime = null): bool
	{
		$data = $this->getValidationData($currentTime);
		$data->setIssuer($this->issuer);
		$data->setAudience($this->audiance);
		$data->setId($this->id);
		$data->setCurrentTime(time() + 60);

		return $token->validate($data);
	}
}