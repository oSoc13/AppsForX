<?php

/*
 * Plugin Name: Apps
 * Plugin URI: http://wpapp
 * Description: Adding apps and ideas to a WordPress blog
 * Version: 1.0
 * Author: Cedric Van Bockhaven
 * Author URI: http://ce3c.be
 * License: Other

 * Copyright: 2013 open Summer of code / OKFN Belgium

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

class WPApps {
    var $dbtable = 'wpapps';
    var $plugin_version = '1.0';
    var $tpls;
    var $options;

    public function __construct() {
        global $wpdb;
        define('IN_WPAPPS', 1);
        $this->dbtable = $wpdb->prefix . $this->dbtable;
        $this->get_options();
        $this->create_update_db_table();

        //debug
        restore_error_handler();
        error_reporting(E_ALL);
        ini_set('error_reporting', E_ALL);
        ini_set('html_errors',TRUE);
        ini_set('display_errors',TRUE);


        if (is_admin()) {

            // Add admin menu hook
            add_action('admin_menu', function() {
                add_menu_page("Apps4X", "Apps4X", "edit_others_posts", "wpapps", array(&$this, "page_overview"), null, (string)(27+M_PI)); // rule of pi
                add_submenu_page("wpapps", "Overview", "Overview", "edit_others_posts", "wpapps", array(&$this, "page_overview")); // overwrite menu title
                add_submenu_page("wpapps", "Cocreation events", "Events", "edit_others_posts", "wpapps_events", array(&$this, "page_events"));
                add_submenu_page("wpapps", "App concept ideas", "Ideas", "edit_others_posts", "wpapps_ideas", array(&$this, "page_ideas"));
            });


            // Add admin css
            add_action('admin_init', function() {
                wp_register_style('wpapps-admin', $this->getpluginurl().'wpapps-admin.css' , array(), $this->plugin_version);
                wp_enqueue_style('wpapps-admin');
            });
        }
    }

    function page_overview() {
        global $wpdb, $wp_query;

        $counts = $wpdb->get_row("SELECT
            (SELECT COUNT(*) FROM {$this->dbtable}_events) AS events_all,
            (SELECT COUNT(*) FROM {$this->dbtable}_ideas) AS ideas_all,
            (SELECT COUNT(*) FROM {$this->dbtable}_ideas WHERE published = 0) AS ideas_pending
        ");

        $statuses = ["All events", /*"All ideas",*/ "Pending ideas"];
        $filter_idx = @$statuses[$_GET['adm_filter']] ? (int)$_GET['adm_filter'] : 0;

        require_once(dirname(__FILE__)."/events_overview.tbl.php");
        $testListTable = new WPApps_EventsOverviewTable();
        $testListTable->prepare_items();

        include(dirname(__FILE__).'/events_overview.tpl.php');
    }

    function page_events() {
        global $wpdb;

        $counts = $wpdb->get_row("SELECT
            (SELECT COUNT(*) FROM {$this->dbtable}_events) AS events_all
        ");

        require_once(dirname(__FILE__)."/page_events.tbl.php");
        $eventsTable = new WPApps_EventsOverviewTable();
        $eventsTable->prepare_items();

        include(dirname(__FILE__).'/page_events.tpl.php');
    }

    function create_update_db_table() {
        if ($this->options['plugin_version'] == $this->plugin_version) return;

        require_once( ABSPATH . '/wp-admin/includes/upgrade.php');

        $sqltext = file_get_contents(dirname(__FILE__)."/wpapps.sql");
        $sqleval = eval('return <<<SQL'.PHP_EOL.$sqltext.PHP_EOL.'SQL;'.PHP_EOL); // eval is evil

        $this->options["plugin_version"] = $this->plugin_version;
        update_option("wpapps_options", $this->options);

        dbDelta($sqleval);
    }

    function get_options() {
        $defaults = [
            "plugin_version" => 0
        ];

        $this->options = get_option("wpapps_options", $defaults);
    }

    // from WPCR

    function getpluginurl() {
        return trailingslashit(plugins_url(basename(dirname(__FILE__))));
    }
}

if (!defined('IN_WPAPPS')) {
    global $WPApps;
    $WPApps = new WPApps();
}