<?php

/*
 * Plugin Name: Apps
 * Plugin URI: http://wpapp
 * Description: Adding apps and ideas to a WordPress blog
 * Version: 1.0
 * Author: Cedric Van Bockhaven
 * Author URI: http://ce3c.be

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

defined('ABSPATH') || exit;

class WPApps {
    const WPAPPS_VERSION = '1.0';
    const WPAPPS_DBTABLE = 'wpapps';

    // setvars
    var $dbtable, $options;
    // classvars
    var $database, $posttypes, $metaboxes;

    function __construct() {
        global $wpdb;

        define('IN_WPAPPS', 1);
        define('WPAPPS_DEBUG', true);
        define('WPAPPS_URL', plugin_dir_url(__FILE__));
        define('WPAPPS_PATH', plugin_dir_path(__FILE__));

        $this->dbtable = $wpdb->prefix . self::WPAPPS_DBTABLE;

        $this->setup_options();
        $this->setup_debug();

        require_once WPAPPS_PATH . '/database.php';
        require_once WPAPPS_PATH . '/posttypes.php';
        require_once WPAPPS_PATH . '/metaboxes.php';

        $this->database = new WPApps_Database($this);
        $this->posttypes = new WPApps_Posttypes($this);
        $this->metaboxes = new WPApps_Metaboxes($this);

        $this->setup_hooks();
    }

    function page_overview() {}
    function page_events() { // ToDo: separate
        global $wpdb, $submenu_file;

        if (@$_GET["action"] == "add") {

        } else {
            // leave room for other possible count(*)'s
            $counts = $wpdb->get_row("SELECT
                (SELECT COUNT(*) FROM {$this->dbtable}_events) AS events_all
            ");

            require_once(dirname(__FILE__)."/page_events.tbl.php");
            $eventsTable = new WPApps_EventsTable($this);
            $eventsTable->prepare_items();

            include(dirname(__FILE__).'/page_events.tpl.php');
        }
    }

    function setup_hooks() {  // ToDo: separate?
        if (is_admin()) { // backend
            // Add admin menu hook

            // maintain highlighting, the hard way
            // not sure if this is the correct action to hook into, but it seems to work
            add_action('admin_head', function() {
                global $submenu_file, $parent_file;
                if (@$_GET["post_type"] == "event" || (get_post() && get_post_type(get_the_ID()) == "event")) {
                    $parent_file = "wpapps";
                    $submenu_file = "edit.php?post_type=event";
                }
            });

            add_action('admin_menu', function() {
                add_menu_page("Apps4X", "Apps4X", "edit_others_posts", "wpapps", [$this, "page_overview"], null, (string)(27+M_PI)); // rule of pi

                add_submenu_page("wpapps", "Overview", "Overview", "edit_others_posts", "wpapps", [$this, "page_overview"]); // overwrite menu title
                add_submenu_page("wpapps", "Events", "Events", "edit_others_posts", "edit.php?post_type=event");
                add_submenu_page("wpapps", "App concept ideas", "Ideas", "edit_others_posts", "wpapps_ideas", [$this, "page_ideas"]);
            });

            // Add admin css
            add_action('admin_init', function() {
                wp_register_style('wpapps-admin', WPAPPS_URL . '/wpapps-admin.css', [],  self::WPAPPS_VERSION);
                wp_enqueue_style('wpapps-admin');
            });
        }
        else { // frontend
            add_action('wp_print_styles', function() {
                wp_register_style('wpapps', WPAPPS_URL.'/wpapps.css');
                wp_enqueue_style('wpapps');
            });
        }
    }

    function setup_options() {
        $defaults = [
            "plugin_version" => 0
        ];

        $this->options = get_option("wpapps_options", $defaults);
    }

    function setup_debug() {
        restore_error_handler();
        error_reporting(E_ALL);
        ini_set('error_reporting', E_ALL);
        ini_set('html_errors',TRUE);
        ini_set('display_errors',TRUE);
    }
}

new WPApps;