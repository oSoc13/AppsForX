<?php

/*
 * Author: Cedric Van Bockhaven
 * Copyright: OKFN Belgium (some rights reserved)
 * License: GPL2

 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class WPApps_Database {
    var $main;

    function __construct($main) {
        $this->main = $main;
    }

    function compare_db_versions() {
        if ($this->main->options['plugin_version'] == WPApps::WPAPPS_VERSION)
            return;

        require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
        $this->crud_events_table();

        $this->main->options["plugin_version"] =  WPApps::WPAPPS_VERSION;
        update_option("wpapps_options", $this->main->options);
    }

    function crud_events_table() {
        $table = "
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
        ";

        dbDelta($table);
    }
}