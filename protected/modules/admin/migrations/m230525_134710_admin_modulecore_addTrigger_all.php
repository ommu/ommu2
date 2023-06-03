<?php
/**
 * m230525_134710_admin_modulecore_addTrigger_all
 *
 * @author Putra Sudaryanto <dwptr@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 25 May 2023, 10:31 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use Yii;
use yii\db\Schema;

class m230525_134710_admin_modulecore_addTrigger_all extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP TRIGGER IF EXISTS `coreBeforeUpdatePages`');
		$this->execute('DROP TRIGGER IF EXISTS `coreAfterDeletePages`');
		$this->execute('DROP TRIGGER IF EXISTS `coreAfterInsertPageViews`');
		$this->execute('DROP TRIGGER IF EXISTS `coreBeforeUpdatePageViews`');
		$this->execute('DROP TRIGGER IF EXISTS `coreAfterUpdatePageViews`');
		$this->execute('DROP TRIGGER IF EXISTS `coreBeforeInsertZoneCountry`');
		$this->execute('DROP TRIGGER IF EXISTS `coreBeforeUpdateZoneCountry`');
		$this->execute('DROP TRIGGER IF EXISTS `coreBeforeInsertZoneProvince`');
		$this->execute('DROP TRIGGER IF EXISTS `coreBeforeUpdateZoneProvince`');
		$this->execute('DROP TRIGGER IF EXISTS `coreBeforeInsertZoneCity`');
		$this->execute('DROP TRIGGER IF EXISTS `coreBeforeUpdateZoneCity`');
		$this->execute('DROP TRIGGER IF EXISTS `coreBeforeInsertZoneDistricts`');
		$this->execute('DROP TRIGGER IF EXISTS `coreBeforeUpdateZoneDistricts`');
		$this->execute('DROP TRIGGER IF EXISTS `coreBeforeInsertZoneVillage`');
		$this->execute('DROP TRIGGER IF EXISTS `coreBeforeUpdateZoneVillage`');
		$this->execute('DROP TRIGGER IF EXISTS `coreBeforeUpdateSettings`');
		$this->execute('DROP TRIGGER IF EXISTS `coreBeforeUpdateLanguage`');
		$this->execute('DROP TRIGGER IF EXISTS `coreBeforeUpdateTags`');


		// alter trigger coreBeforeUpdatePages
		$alterTriggerCoreBeforeUpdatePages = <<< SQL
CREATE
    TRIGGER `coreBeforeUpdatePages` BEFORE UPDATE ON `ommu_core_pages` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;
    END;
SQL;
		$this->execute($alterTriggerCoreBeforeUpdatePages);

		// alter trigger coreAfterDeletePages
		$alterTriggerCoreAfterDeletePages = <<< SQL
CREATE
    TRIGGER `coreAfterDeletePages` AFTER DELETE ON `ommu_core_pages` 
    FOR EACH ROW BEGIN
	/*
	DELETE FROM `source_message` WHERE `id`=OLD.name;
	DELETE FROM `source_message` WHERE `id`=OLD.desc;
	DELETE FROM `source_message` WHERE `id`=OLD.quote;
	*/
	UPDATE `source_message` SET `message`=CONCAT(message,'_DELETED') WHERE `id`=OLD.name;
	UPDATE `source_message` SET `message`=CONCAT(message,'_DELETED') WHERE `id`=OLD.desc;
	UPDATE `source_message` SET `message`=CONCAT(message,'_DELETED') WHERE `id`=OLD.quote;
    END;
SQL;
		$this->execute($alterTriggerCoreAfterDeletePages);

		// alter trigger coreAfterInsertPageViews
		$alterTriggerCoreAfterInsertPageViews = <<< SQL
CREATE
    TRIGGER `coreAfterInsertPageViews` AFTER INSERT ON `ommu_core_page_views` 
    FOR EACH ROW BEGIN
	IF (NEW.publish = 1 AND NEW.views <> 0) THEN
		INSERT `ommu_core_page_view_history` (`view_id`, `view_date`, `view_ip`)
		VALUE (NEW.view_id, NEW.view_date, NEW.view_ip);
	END IF;
    END;
SQL;
		$this->execute($alterTriggerCoreAfterInsertPageViews);

		// alter trigger coreBeforeUpdatePageViews
		$alterTriggerCoreBeforeUpdatePageViews = <<< SQL
CREATE
    TRIGGER `coreBeforeUpdatePageViews` BEFORE UPDATE ON `ommu_core_page_views` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		IF (NEW.publish = 0) THEN
			SET NEW.deleted_date = NOW();
		END IF;
	ELSE
		IF (NEW.publish = 1 AND NEW.views <> OLD.views AND NEW.views > OLD.views) THEN
			SET NEW.view_date = NOW();
		END IF;
	END IF;
    END;
SQL;
		$this->execute($alterTriggerCoreBeforeUpdatePageViews);

		// alter trigger coreAfterUpdatePageViews
		$alterTriggerCoreAfterUpdatePageViews = <<< SQL
CREATE
    TRIGGER `coreAfterUpdatePageViews` AFTER UPDATE ON `ommu_core_page_views` 
    FOR EACH ROW BEGIN
	IF (NEW.view_date <> OLD.view_date) THEN
		INSERT `ommu_core_page_view_history` (`view_id`, `view_date`, `view_ip`)
		VALUE (NEW.view_id, NEW.view_date, NEW.view_ip);
	END IF;
    END;
SQL;
		$this->execute($alterTriggerCoreAfterUpdatePageViews);

		// alter trigger coreBeforeInsertZoneCountry
		$alterTriggerCoreBeforeInsertZoneCountry = <<< SQL
CREATE
    TRIGGER `coreBeforeInsertZoneCountry` BEFORE INSERT ON `ommu_core_zone_country` 
    FOR EACH ROW BEGIN
	DECLARE slug_tr TEXT;
	
	SET NEW.country_name = TRIM(NEW.country_name);
	
	IF (NEW.slug IS NULL) THEN
		CALL syncSlug(NEW.country_name, slug_tr);
		SET NEW.slug = slug_tr;
	END IF;	
    END;
SQL;
		$this->execute($alterTriggerCoreBeforeInsertZoneCountry);

		// alter trigger coreBeforeUpdateZoneCountry
		$alterTriggerCoreBeforeUpdateZoneCountry = <<< SQL
CREATE
    TRIGGER `coreBeforeUpdateZoneCountry` BEFORE UPDATE ON `ommu_core_zone_country` 
    FOR EACH ROW BEGIN
	DECLARE slug_tr TEXT;
	
	SET NEW.country_name = TRIM(NEW.country_name);
	
	CALL syncSlug(NEW.country_name, slug_tr);
	SET NEW.slug = slug_tr;
    END;
SQL;
		$this->execute($alterTriggerCoreBeforeUpdateZoneCountry);

		// alter trigger coreBeforeInsertZoneProvince
		$alterTriggerCoreBeforeInsertZoneProvince = <<< SQL
CREATE
    TRIGGER `coreBeforeInsertZoneProvince` BEFORE INSERT ON `ommu_core_zone_province` 
    FOR EACH ROW BEGIN
	DECLARE slug_tr TEXT;
	
	SET NEW.province_name = TRIM(NEW.province_name);
	
	IF (NEW.slug IS NULL) THEN
		CALL syncSlug(NEW.province_name, slug_tr);
		SET NEW.slug = slug_tr;
	END IF;	
    END;
SQL;
		$this->execute($alterTriggerCoreBeforeInsertZoneProvince);

		// alter trigger coreBeforeUpdateZoneProvince
		$alterTriggerCoreBeforeUpdateZoneProvince = <<< SQL
CREATE
    TRIGGER `coreBeforeUpdateZoneProvince` BEFORE UPDATE ON `ommu_core_zone_province` 
    FOR EACH ROW BEGIN
	DECLARE slug_tr TEXT;
	
	SET NEW.province_name = TRIM(NEW.province_name);
	
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;
	
	CALL syncSlug(NEW.province_name, slug_tr);
	SET NEW.slug = slug_tr;
    END;
SQL;
		$this->execute($alterTriggerCoreBeforeUpdateZoneProvince);

		// alter trigger coreBeforeInsertZoneCity
		$alterTriggerCoreBeforeInsertZoneCity = <<< SQL
CREATE
    TRIGGER `coreBeforeInsertZoneCity` BEFORE INSERT ON `ommu_core_zone_city` 
    FOR EACH ROW BEGIN
	DECLARE province_id_tr INT;
	DECLARE slug_tr TEXT;
	
	SET NEW.city_name = TRIM(NEW.city_name);
	
	IF (NEW.province_id IS NULL) THEN
		CALL coreGetZoneProvinceWithCityMfdonline(NEW.mfdonline, province_id_tr);
		
		IF (province_id_tr IS NOT NULL) THEN
			SET NEW.province_id = province_id_tr;
		END IF;	
	END IF;	
	
	IF (NEW.slug IS NULL) THEN
		CALL syncSlug(NEW.city_name, slug_tr);
		SET NEW.slug = slug_tr;
	END IF;	
    END;
SQL;
		$this->execute($alterTriggerCoreBeforeInsertZoneCity);

		// alter trigger coreBeforeUpdateZoneCity
		$alterTriggerCoreBeforeUpdateZoneCity = <<< SQL
CREATE
    TRIGGER `coreBeforeUpdateZoneCity` BEFORE UPDATE ON `ommu_core_zone_city` 
    FOR EACH ROW BEGIN
	DECLARE province_id_tr SMALLINT;
	DECLARE slug_tr TEXT;
	
	SET NEW.city_name = TRIM(NEW.city_name);
	
	IF (NEW.mfdonline <> OLD.mfdonline) THEN
		CALL coreGetZoneProvinceWithCityMfdonline(NEW.mfdonline, province_id_tr);
		IF (province_id_tr IS NOT NULL) THEN
			SET NEW.province_id = province_id_tr;
		END IF;
	END IF;
	
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;
	
	CALL syncSlug(NEW.city_name, slug_tr);
	SET NEW.slug = slug_tr;
    END;
SQL;
		$this->execute($alterTriggerCoreBeforeUpdateZoneCity);

		// alter trigger coreBeforeInsertZoneDistricts
		$alterTriggerCoreBeforeInsertZoneDistricts = <<< SQL
CREATE
    TRIGGER `coreBeforeInsertZoneDistricts` BEFORE INSERT ON `ommu_core_zone_district` 
    FOR EACH ROW BEGIN
	DECLARE `city_id_tr` INT;
	DECLARE slug_tr TEXT;
	
	SET NEW.district_name = TRIM(NEW.district_name);
	
	IF (NEW.city_id IS NULL) THEN
		CALL coreGetZoneCityWithDistrictMfdonline(NEW.mfdonline, city_id_tr);
		
		IF (city_id_tr IS NOT NULL) THEN
			SET NEW.city_id = city_id_tr;
		END IF;	
	END IF;	
	
	IF (NEW.slug IS NULL) THEN
		CALL syncSlug(NEW.district_name, slug_tr);
		SET NEW.slug = slug_tr;
	END IF;	
    END;
SQL;
		$this->execute($alterTriggerCoreBeforeInsertZoneDistricts);

		// alter trigger coreBeforeUpdateZoneDistricts
		$alterTriggerCoreBeforeUpdateZoneDistricts = <<< SQL
CREATE
    TRIGGER `coreBeforeUpdateZoneDistricts` BEFORE UPDATE ON `ommu_core_zone_district` 
    FOR EACH ROW BEGIN
	DECLARE `city_id_tr` INT;
	DECLARE slug_tr TEXT;
	
	SET NEW.district_name = TRIM(NEW.district_name);
	
	IF (NEW.mfdonline <> OLD.mfdonline) THEN
		CALL coreGetZoneCityWithDistrictMfdonline(NEW.mfdonline, city_id_tr);
		IF (city_id_tr IS NOT NULL) THEN
			SET NEW.city_id = city_id_tr;
		END IF;
	END IF;
	
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;
	
	CALL syncSlug(NEW.district_name, slug_tr);
	SET NEW.slug = slug_tr;
    END;
SQL;
		$this->execute($alterTriggerCoreBeforeUpdateZoneDistricts);

		// alter trigger coreBeforeInsertZoneVillage
		$alterTriggerCoreBeforeInsertZoneVillage = <<< SQL
CREATE
    TRIGGER `coreBeforeInsertZoneVillage` BEFORE INSERT ON `ommu_core_zone_village` 
    FOR EACH ROW BEGIN
	DECLARE `district_id_tr` INT;
	DECLARE slug_tr TEXT;
	
	SET NEW.village_name = TRIM(NEW.village_name);
	SET NEW.zipcode = TRIM(NEW.zipcode);
	
	IF (NEW.district_id IS NULL) THEN
		CALL coreGetZoneDistrictWithVillageMfdonline(NEW.mfdonline, district_id_tr);
		
		IF (district_id_tr IS NOT NULL) THEN
			SET NEW.district_id = district_id_tr;
		END IF;
	END IF;
	
	IF (NEW.slug IS NULL) THEN
		CALL syncSlug(NEW.village_name, slug_tr);
		SET NEW.slug = slug_tr;
	END IF;
    END;
SQL;
		$this->execute($alterTriggerCoreBeforeInsertZoneVillage);

		// alter trigger coreBeforeUpdateZoneVillage
		$alterTriggerCoreBeforeUpdateZoneVillage = <<< SQL
CREATE
    TRIGGER `coreBeforeUpdateZoneVillage` BEFORE UPDATE ON `ommu_core_zone_village` 
    FOR EACH ROW BEGIN
	DECLARE `district_id_tr` INT;
	DECLARE slug_tr TEXT;
	
	SET NEW.village_name = TRIM(NEW.village_name);
	SET NEW.zipcode = TRIM(NEW.zipcode);
	
	IF (NEW.mfdonline <> OLD.mfdonline) THEN
		CALL coreGetZoneDistrictWithVillageMfdonline(NEW.mfdonline, district_id_tr);
		IF (district_id_tr IS NOT NULL) THEN
			SET NEW.district_id = district_id_tr;
		END IF;
	END IF;
	
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;
	
	CALL syncSlug(NEW.village_name, slug_tr);
	SET NEW.slug = slug_tr;
    END;
SQL;
		$this->execute($alterTriggerCoreBeforeUpdateZoneVillage);

		// alter trigger coreBeforeUpdateSettings
		$alterTriggerCoreBeforeUpdateSettings = <<< SQL
CREATE
    TRIGGER `coreBeforeUpdateSettings` BEFORE UPDATE ON `ommu_core_settings` 
    FOR EACH ROW BEGIN
	/*
	IF (NEW.site_type <> OLD.site_type) THEN
		IF (NEW.site_type = 0) THEN
			SET NEW.signup_username = 0;
			SET NEW.signup_approve = 1;
			SET NEW.signup_verifyemail = 0;
			SET NEW.signup_photo = 0;
			SET NEW.signup_welcome = 0;
			SET NEW.signup_random = 0;
			SET NEW.signup_terms = 0;
			SET NEW.signup_invitepage = 0;
			SET NEW.signup_inviteonly = 0;
			SET NEW.signup_checkemail = 0;
			SET NEW.spam_signup = 0;
		ELSE
			SET NEW.signup_approve = 0;
			SET NEW.signup_verifyemail = 1;
			SET NEW.signup_random = 1;
			SET NEW.signup_terms = 1;
			SET NEW.signup_adminemail = 1;
		END IF;
	END IF;
	*/
    END;
SQL;
		$this->execute($alterTriggerCoreBeforeUpdateSettings);

		// alter trigger coreBeforeUpdateLanguage
		$alterTriggerCoreBeforeUpdateLanguage = <<< SQL
CREATE
    TRIGGER `coreBeforeUpdateLanguage` BEFORE UPDATE ON `ommu_core_languages` 
    FOR EACH ROW BEGIN
	IF (NEW.actived <> OLD.actived) THEN
		SET NEW.updated_date = NOW();
	END IF;
    END;
SQL;
		$this->execute($alterTriggerCoreBeforeUpdateLanguage);

		// alter trigger coreBeforeUpdateTags
		$alterTriggerCoreBeforeUpdateTags = <<< SQL
CREATE
    TRIGGER `coreBeforeUpdateTags` BEFORE UPDATE ON `ommu_core_tags` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;
    END;
SQL;
		$this->execute($alterTriggerCoreBeforeUpdateTags);
	}

	public function down()
	{
        $this->execute('DROP TRIGGER IF EXISTS `coreBeforeUpdatePages`');
        $this->execute('DROP TRIGGER IF EXISTS `coreAfterDeletePages`');
        $this->execute('DROP TRIGGER IF EXISTS `coreAfterInsertPageViews`');
        $this->execute('DROP TRIGGER IF EXISTS `coreBeforeUpdatePageViews`');
        $this->execute('DROP TRIGGER IF EXISTS `coreAfterUpdatePageViews`');
        $this->execute('DROP TRIGGER IF EXISTS `coreBeforeInsertZoneCountry`');
        $this->execute('DROP TRIGGER IF EXISTS `coreBeforeUpdateZoneCountry`');
        $this->execute('DROP TRIGGER IF EXISTS `coreBeforeInsertZoneProvince`');
        $this->execute('DROP TRIGGER IF EXISTS `coreBeforeUpdateZoneProvince`');
        $this->execute('DROP TRIGGER IF EXISTS `coreBeforeInsertZoneCity`');
        $this->execute('DROP TRIGGER IF EXISTS `coreBeforeUpdateZoneCity`');
        $this->execute('DROP TRIGGER IF EXISTS `coreBeforeInsertZoneDistricts`');
        $this->execute('DROP TRIGGER IF EXISTS `coreBeforeUpdateZoneDistricts`');
        $this->execute('DROP TRIGGER IF EXISTS `coreBeforeInsertZoneVillage`');
        $this->execute('DROP TRIGGER IF EXISTS `coreBeforeUpdateZoneVillage`');
        $this->execute('DROP TRIGGER IF EXISTS `coreBeforeUpdateSettings`');
        $this->execute('DROP TRIGGER IF EXISTS `coreBeforeUpdateLanguage`');
        $this->execute('DROP TRIGGER IF EXISTS `coreBeforeUpdateTags`');
	}
}
