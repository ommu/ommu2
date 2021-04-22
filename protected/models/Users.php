<?php
/**
 * Users
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2020 OMMU (www.ommu.id)
 * @created date 12 May 2020, 11:57 WIB
 * @link https://github.com/ommu/ommu
 *
 */

namespace app\models;

use Yii;
use ommu\users\models\Users as UsersModel;

class Users extends UsersModel
{
	/**
	 * {@inheritdoc}
	 */
	public $photos;

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->photos = join('/', [self::getUploadPath(false), 'default.png']);
        // if (isset($this->member)) {
        //     $uploadPath = join('/', [Members::getUploadPath(false), $this->user->member_id]);
        //     $photos = $this->member->photo_profile ? join('/', [$uploadPath, $this->member->photo_profile]) : '';
        //     $this->photos = ($photos != '' && file_exists($photos)) ? $photos : join('/', [Members::getUploadPath(false), 'default.png']);
        // } else {
        //     $this->photos = join('/', [self::getUploadPath(false), 'default.png']);
        // }
	}
}
