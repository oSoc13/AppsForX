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

    public function __construct() {
        if (is_admin()) {
            // Add admin menu hook
            add_action('admin_menu', function() {
                add_menu_page("Apps", "Apps", "edit_others_posts"/*capability*/, "wpapps", array(&$this, "admin_wpapps"), null, '20.123');
            });
        }
    }

/*    function WPApps() {
        global $wpdb;

        define('IN_WPAPPS', 1);

        restore_error_handler();
        error_reporting(E_ALL);
        ini_set('error_reporting', E_ALL);
        ini_set('html_errors',TRUE);
        ini_set('display_errors',TRUE);

        $this->dbtable = $wpdb->prefix . $this->dbtable;


        
    }*/

    function admin_wpapps() {
        global $wpdb;

        $wpdb->get_var("SELECT COUNT(*) FROM `$this->dbtable` WHERE `status`=0")

        echo "hai";
    }

    // from WPCR

    function getpluginurl() {
        return trailingslashit(plugins_url(basename(dirname(__FILE__))));
    }
}

if (!defined('IN_WPAPPS')) {
    global $WPApps;
    $WPApps = new WPApps();
    register_activation_hook(__FILE__, array(&$WPApps, 'activate'));
    register_deactivation_hook(__FILE__, array(&$WPApps, 'deactivate'));
}