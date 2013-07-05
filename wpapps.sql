CREATE TABLE {$this->dbtable}_events (
  `event_id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NULL ,
  `short_title` VARCHAR(255) NULL ,
  `where_location` VARCHAR(255) NULL ,
  `when_start` DATETIME NULL ,
  `when_end` DATETIME NULL ,
  `participants_est` INT NULL ,
  `registration_url` VARCHAR(255) NULL ,
  `offers_awards` TINYINT(1) NULL ,
  `logo` BLOB NULL ,
  `social_trackers` BLOB NULL ,
  `contact_email` VARCHAR(255) NULL ,
  `contact_phone` VARCHAR(255) NULL ,
  `contact_address` VARCHAR(255) NULL ,
  `parent_event_id` INT NULL ,
  PRIMARY KEY (`event_id`) ,
  KEY `fk_wpapps_events_wpapps_events_idx` (`parent_event_id` ASC)
)