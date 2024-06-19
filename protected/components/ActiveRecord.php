<?php
/**
 * ActiveRecord class
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 7 December 2017, 22:15 WIB
 * @link https://github.com/ommu/ommu
 */

namespace app\components;

use Yii;

class ActiveRecord extends \yii\db\ActiveRecord
{
	use \ommu\traits\GridViewTrait;

	/**
	 * @var array tempat menyimpan daftar kolom yang ditampilkan gridview.
	 */
	public $defaultColumns = [];
	/** 
	 * @var array tempat menyimpan daftar dan template kolom yang ditampilkan gridview.
	 */
	public $templateColumns = [];
	/**
	 * @var array tempat menyimpan daftar kolom yg tidak ditampilkan gridview dalam kondisi default.
	 */
	public $gridForbiddenColumn = [];

	/**
	 * (@inheritdoc)
	 */
	protected $_labels;

	/**
	 * Mengembalikan data kolom yang di ditampilkan gridview.
	 *
	 * @param array $columns daftar kolom (get-request "GridColumn")
	 * @return array kolom yang ditampilan gridview
	 */
	public function getGridColumn($columns=null) 
	{
		// jika $column didefinisikan pada grid-option
		if (empty($columns) || $columns == null) {
			array_splice($this->defaultColumns, 0);
			foreach($this->templateColumns as $key => $val) {
				if (!in_array($key, $this->gridForbiddenColumn) && !in_array($key, $this->defaultColumns)) {
					$this->defaultColumns[] = $val;
                }
			}
			return $this->defaultColumns;
		}

		foreach($columns as $val) {
			if (!in_array($val, $this->gridForbiddenColumn) && !in_array($val, $this->defaultColumns)) {
				$col = $this->getTemplateColumn($val);
				if ($col != null) {
					$this->defaultColumns[] = $col;
                }
			}
		}

		array_unshift($this->defaultColumns, [
			'header' => '#',
			'class' => 'app\components\grid\SerialColumn',
			'contentOptions' => ['class' => 'text-center'],
		]);

		return $this->defaultColumns;
	}

	/**
	 * Mengembalikan template kolom berdasarkan nama/id attribute
	 *
	 * @param string $name nama/id attribute
	 * @return mixed
	 */
	public function getTemplateColumn($name) 
	{
		$data = null;
		if (trim($name) == '') {
			return $data;
        }

		foreach($this->templateColumns as $key => $item) {
			if ($name == $key) {
				$data = $item;
				break;
			}
		}
		return $data;
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
	 * (@inheritdoc)
	 */
	public function loadParams() {
		return func_get_args();
	}

	/**
	 * (@inheritdoc)
	 * Hapus semua data pd tabel, karena jika tabel ada fk biasanya tidak bisa ditruncate.
	 *
	 * @return void
	 */
	public static function truncateTable() 
	{
		$pkColumn = '';
		$pk = self::getTableSchema()->primaryKey;
		if (is_array($pk) && count($pk)) {
			$pkColumn = $pk[0];
		}

		if ($pk == '') return;

		$sql = sprintf("DELETE FROM %s WHERE %s > 0", self::tableName(), $pkColumn);
		try {
			self::getDb()->createCommand($sql)->execute();
		} catch(\Exception $e) {}
	}

	/**
	 * (@inheritdoc)
	 * Reset auto increment pada tabel.
	 *
	 * @return void
	 */
	public static function resetAutoIncrement($ai = 1)
	{
		$pkColumn = '';
		$pk = self::getTableSchema()->primaryKey;
		if (is_array($pk) && count($pk)) {
			$pkColumn = $pk[0];
		}

		if ($pk == '') return;
		$sql = sprintf("ALTER TABLE %s AUTO_INCREMENT = %d", self::tableName(), $ai);
		try {
			self::getDb()->createCommand($sql)->execute();
		} catch(\Exception $e) {}
	}

	/**
	 * (@inheritdoc)
	 * https://stackoverflow.com/questions/25522462/yii2-rest-query#answer-25618361
	 * 
	 */
	public function formName()
    {
		return '';
	}
}
