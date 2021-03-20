<?php
/**
 * Formatter class
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 7 December 2017, 05:36 WIB
 * @link https://github.com/ommu/ommu
 */

namespace app\components\i18n;

class Formatter extends \yii\i18n\Formatter
{
	protected $formatDateTime;

	/**
	 * @var string the text to be displayed when formatting a `null` value. Defaults '-'
	 */
	public $nullDisplay = '-';
	/**
	 * @var int panjang karakter yang akan ditampilkan. default 10
	 */
	public $shortImageLength = 10;

	/**
	 * Formats the value as a date.
	 * @param int|string|DateTime $value the value to be formatted. The following
	 * types of value are supported:
	 *
	 * - an integer representing a UNIX timestamp. A UNIX timestamp is always in UTC by its definition.
	 * - a string that can be [parsed to create a DateTime object](http://php.net/manual/en/datetime.formats.php).
	 *   The timestamp is assumed to be in [[defaultTimeZone]] unless a time zone is explicitly given.
	 * - a PHP [DateTime](http://php.net/manual/en/class.datetime.php) object. You may set the time zone
	 *   for the DateTime object to specify the source time zone.
	 *
	 * The formatter will convert date values according to [[timeZone]] before formatting it.
	 * If no timezone conversion should be performed, you need to set [[defaultTimeZone]] and [[timeZone]] to the same value.
	 * Also no conversion will be performed on values that have no time information, e.g. `"2017-06-05"`.
	 *
	 * @param string $format the format used to convert the value into a date string.
	 * If null, [[dateFormat]] will be used.
	 *
	 * This can be "short", "medium", "long", or "full", which represents a preset format of different lengths.
	 * It can also be a custom format as specified in the [ICU manual](http://userguide.icu-project.org/formatparse/datetime).
	 *
	 * Alternatively this can be a string prefixed with `php:` representing a format that can be recognized by the
	 * PHP [date()](http://php.net/manual/en/function.date.php)-function.
	 *
	 * @return string the formatted result.
	 * @throws InvalidArgumentException if the input value can not be evaluated as a date value.
	 * @throws InvalidConfigException if the date format is invalid.
	 * @see dateFormat
	 */
	public function asDate($value, $format = null)
	{
		if (in_array(date('Y-m-d', strtotime($value)), ['0000-00-00', '1970-01-01', '0002-12-02', '-0001-11-30'])) {
			return $this->nullDisplay;
        }

		return parent::asDate($value, $format);
	}

	/**
	 * Formats the value as a time.
	 * @param int|string|DateTime $value the value to be formatted. The following
	 * types of value are supported:
	 *
	 * - an integer representing a UNIX timestamp. A UNIX timestamp is always in UTC by its definition.
	 * - a string that can be [parsed to create a DateTime object](http://php.net/manual/en/datetime.formats.php).
	 *   The timestamp is assumed to be in [[defaultTimeZone]] unless a time zone is explicitly given.
	 * - a PHP [DateTime](http://php.net/manual/en/class.datetime.php) object. You may set the time zone
	 *   for the DateTime object to specify the source time zone.
	 *
	 * The formatter will convert date values according to [[timeZone]] before formatting it.
	 * If no timezone conversion should be performed, you need to set [[defaultTimeZone]] and [[timeZone]] to the same value.
	 *
	 * @param string $format the format used to convert the value into a date string.
	 * If null, [[timeFormat]] will be used.
	 *
	 * This can be "short", "medium", "long", or "full", which represents a preset format of different lengths.
	 * It can also be a custom format as specified in the [ICU manual](http://userguide.icu-project.org/formatparse/datetime).
	 *
	 * Alternatively this can be a string prefixed with `php:` representing a format that can be recognized by the
	 * PHP [date()](http://php.net/manual/en/function.date.php)-function.
	 *
	 * @return string the formatted result.
	 * @throws InvalidArgumentException if the input value can not be evaluated as a date value.
	 * @throws InvalidConfigException if the date format is invalid.
	 * @see timeFormat
	 */
	public function asTime($value, $format = null)
	{
		if (in_array($value, ['0000-00-00 00:00:00', '1970-01-01 00:00:00', '0002-12-02 07:07:12', '-0001-11-30 00:00:00'])) {
			return $this->nullDisplay;
        }

		return parent::asTime($value, $format);
	}

	/**
	 * Formats the value as a datetime.
	 * @param int|string|DateTime $value the value to be formatted. The following
	 * types of value are supported:
	 *
	 * - an integer representing a UNIX timestamp. A UNIX timestamp is always in UTC by its definition.
	 * - a string that can be [parsed to create a DateTime object](http://php.net/manual/en/datetime.formats.php).
	 *   The timestamp is assumed to be in [[defaultTimeZone]] unless a time zone is explicitly given.
	 * - a PHP [DateTime](http://php.net/manual/en/class.datetime.php) object. You may set the time zone
	 *   for the DateTime object to specify the source time zone.
	 *
	 * The formatter will convert date values according to [[timeZone]] before formatting it.
	 * If no timezone conversion should be performed, you need to set [[defaultTimeZone]] and [[timeZone]] to the same value.
	 *
	 * @param string $format the format used to convert the value into a date string.
	 * If null, [[datetimeFormat]] will be used.
	 *
	 * This can be "short", "medium", "long", or "full", which represents a preset format of different lengths.
	 * It can also be a custom format as specified in the [ICU manual](http://userguide.icu-project.org/formatparse/datetime).
	 *
	 * Alternatively this can be a string prefixed with `php:` representing a format that can be recognized by the
	 * PHP [date()](http://php.net/manual/en/function.date.php)-function.
	 *
	 * @return string the formatted result.
	 * @throws InvalidArgumentException if the input value can not be evaluated as a date value.
	 * @throws InvalidConfigException if the date format is invalid.
	 * @see datetimeFormat
	 */
	public function asDatetime($value, $format = null)
	{
		if (in_array($value, ['0000-00-00 00:00:00', '1970-01-01 00:00:00', '0002-12-02 07:07:12', '-0001-11-30 00:00:00'])) {
			return $this->nullDisplay;
        }

		return parent::asDatetime($value, $format);
	}

	/**
	 * Mengembalikan kependekan nama file sesuai setingan panjangnya. jika melebihi setingan panjang karakter
	 * maka akan direplace dengan karakter **
	 *
	 * @return string
	 */
	public function asShortImage($value)
	{
		if ($value === null) {
			return $this->nullDisplay;
        }

		return substr($value, 0, $this->shortImageLength) . '**.' . substr(strrchr($value, '.'), 1);
	}
}
