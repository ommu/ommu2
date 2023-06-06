<?php
/**
 * m230525_134646_admin_modulecore_addStoreProsedure_all
 *
 * @author Putra Sudaryanto <dwptr@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 25 May 2023, 10:31 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\db\Schema;

class m230525_134646_admin_modulecore_addStoreProsedure_all extends \yii\db\Migration
{
	public function up()
	{
        $this->execute('DROP PROCEDURE IF EXISTS `coreGetLanguageDefault`');
        $this->execute('DROP PROCEDURE IF EXISTS `coreGetSetting`');
        $this->execute('DROP PROCEDURE IF EXISTS `coreGetZoneCountryWithProvinceId`');
        $this->execute('DROP PROCEDURE IF EXISTS `coreGetZoneProvinceWithCityId`');
        $this->execute('DROP PROCEDURE IF EXISTS `coreGetZoneProvinceWithCityMfdonline`');
        $this->execute('DROP PROCEDURE IF EXISTS `coreGetZoneCityWithDistrictId`');
        $this->execute('DROP PROCEDURE IF EXISTS `coreGetZoneCityWithDistrictMfdonline`');
        $this->execute('DROP PROCEDURE IF EXISTS `coreGetZoneDistrictWithVillageId`');
        $this->execute('DROP PROCEDURE IF EXISTS `coreGetZoneDistrictWithVillageMfdonline`');

		// alter sp coreGetLanguageDefault
		$alterProsedureCoreGetLanguageDefault = <<< SQL
CREATE PROCEDURE `coreGetLanguageDefault`(OUT `language_id_sp` TINYINT)
BEGIN
	SELECT `language_id` INTO language_id_sp FROM `ommu_core_languages` WHERE `actived`=1 AND `default`=1;
END;
SQL;
		$this->execute($alterProsedureCoreGetLanguageDefault);

		// alter sp coreGetSetting
		$alterProsedureCoreGetSetting = <<< SQL
CREATE PROCEDURE `coreGetSetting`(OUT `signup_numgiven_sp` TINYINT)
BEGIN
	/**
	 * userAfterInsert
	 * supportAfterInsertMails
	 */
	SELECT `signup_numgiven`
	INTO signup_numgiven_sp
	FROM `ommu_core_settings`
	WHERE `id`=1;
END;
SQL;
		$this->execute($alterProsedureCoreGetSetting);

		// alter sp coreGetZoneCountryWithProvinceId
		$alterProsedureCoreGetZoneCountryWithProvinceId = <<< SQL
CREATE PROCEDURE `coreGetZoneCountryWithProvinceId`(IN `province_id_sp` SMALLINT, OUT `country_id_sp` SMALLINT)
BEGIN
	SELECT `country_id` INTO country_id_sp FROM `ommu_core_zone_province` WHERE `province_id`=province_id_sp;
END;
SQL;
		$this->execute($alterProsedureCoreGetZoneCountryWithProvinceId);

		// alter sp coreGetZoneProvinceWithCityId
		$alterProsedureCoreGetZoneProvinceWithCityId = <<< SQL
CREATE PROCEDURE `coreGetZoneProvinceWithCityId`(in `city_id_sp` INT, OUT `province_id_sp` SMALLINT)
BEGIN
	SELECT `province_id` INTO province_id_sp FROM `ommu_core_zone_city` WHERE `city_id`=city_id_sp;
END;
SQL;
		$this->execute($alterProsedureCoreGetZoneProvinceWithCityId);

		// alter sp coreGetZoneProvinceWithCityMfdonline
		$alterProsedureCoreGetZoneProvinceWithCityMfdonline = <<< SQL
CREATE PROCEDURE `coreGetZoneProvinceWithCityMfdonline`(IN `mfdonline_sp` TEXT, OUT `province_id_sp` INT)
BEGIN
	SELECT `province_id` INTO province_id_sp FROM `ommu_core_zone_province` WHERE `mfdonline`=LEFT(mfdonline_sp,2);
END;
SQL;
		$this->execute($alterProsedureCoreGetZoneProvinceWithCityMfdonline);

		// alter sp coreGetZoneCityWithDistrictId
		$alterProsedureCoreGetZoneCityWithDistrictId = <<< SQL
CREATE PROCEDURE `coreGetZoneCityWithDistrictId`(IN `district_id_sp` INT, OUT `city_id_sp` INT)
BEGIN
	SELECT `city_id` INTO city_id_sp FROM `ommu_core_zone_district` WHERE `district_id`=district_id_sp;
END;
SQL;
		$this->execute($alterProsedureCoreGetZoneCityWithDistrictId);

		// alter sp coreGetZoneCityWithDistrictMfdonline
		$alterProsedureCoreGetZoneCityWithDistrictMfdonline = <<< SQL
CREATE PROCEDURE `coreGetZoneCityWithDistrictMfdonline`(IN `mfdonline_sp` TEXT, OUT `city_id_sp` INT)
BEGIN
	SELECT `city_id` INTO city_id_sp FROM `ommu_core_zone_city` WHERE `mfdonline`=LEFT(mfdonline_sp,4);
END;
SQL;
		$this->execute($alterProsedureCoreGetZoneCityWithDistrictMfdonline);

		// alter sp coreGetZoneDistrictWithVillageId
		$alterProsedureCoreGetZoneDistrictWithVillageId = <<< SQL
CREATE PROCEDURE `coreGetZoneDistrictWithVillageId`(IN `village_id_sp` INT, OUT `district_id_sp` INT)
BEGIN
	SELECT `district_id` INTO district_id_sp FROM `ommu_core_zone_village` WHERE `village_id`=village_id_sp;
END;
SQL;
		$this->execute($alterProsedureCoreGetZoneDistrictWithVillageId);

		// alter sp coreGetZoneDistrictWithVillageMfdonline
		$alterProsedureCoreGetZoneDistrictWithVillageMfdonline = <<< SQL
CREATE PROCEDURE `coreGetZoneDistrictWithVillageMfdonline`(IN `mfdonline_sp` TEXT, OUT `district_id_sp` INT)
BEGIN
	SELECT `district_id` INTO district_id_sp FROM `ommu_core_zone_district` WHERE `mfdonline`=LEFT(mfdonline_sp,7);
END;
SQL;
		$this->execute($alterProsedureCoreGetZoneDistrictWithVillageMfdonline);
	}

	public function down()
	{
        $this->execute('DROP PROCEDURE `coreGetLanguageDefault`');
        $this->execute('DROP PROCEDURE `coreGetSetting`');
        $this->execute('DROP PROCEDURE `coreGetZoneCountryWithProvinceId`');
        $this->execute('DROP PROCEDURE `coreGetZoneProvinceWithCityId`');
        $this->execute('DROP PROCEDURE `coreGetZoneProvinceWithCityMfdonline`');
        $this->execute('DROP PROCEDURE `coreGetZoneCityWithDistrictId`');
        $this->execute('DROP PROCEDURE `coreGetZoneCityWithDistrictMfdonline`');
        $this->execute('DROP PROCEDURE `coreGetZoneDistrictWithVillageId`');
        $this->execute('DROP PROCEDURE `coreGetZoneDistrictWithVillageMfdonline`');
	}
}
