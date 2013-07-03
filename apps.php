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

    public function __construct() {
        require_once(dirname(__FILE__)."./templates.php");
        $this->tpls = new WPAppsTemplates();

/*            add_filter('query_vars', function($qvar) {
                die;
                $qvar[] = "adm_filter";
                return $qvar;
            }, 10,1);*/

        if (is_admin()) {


            // Add admin menu hook
            add_action('admin_menu', function() {
                add_menu_page("Apps", "Apps", "edit_others_posts"/*capability*/, "wpapps", array(&$this, "admin_wpapps"), null, '30.'.M_PI);
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
        global $wpdb, $wp_query;


/*        $counts = $wpdb->get_row("SELECT 
            (SELECT COUNT(*) FROM {$this->dbtable}_events) AS events_all,
            (SELECT COUNT(*) FROM {$this->dbtable}_ideas) AS ideas_all,
            (SELECT COUNT(*) FROM {$this->dbtable}_ideas WHERE published = 0) AS ideas_pending
        ");*/

        // $counts->events_all
        // $counts->ideas_all

        $statuses = ["All events", /*"All ideas",*/ "Pending ideas"];
        $filter_idx = isset($statuses[(int)@$_GET['adm_filter']]) ? (int)$_GET['adm_filter'] : 0;

        ?>
        <div class="wrap">
            <div class="icon32" id="icon-edit-comments"><br /></div>
            <h2>Apps - <?=$statuses[$filter_idx]?></h2>

            <ul class="subsubsub">
                <li class="all"><a <?php if (!$filter_idx) { echo 'class="current"'; } ?> href="?page=wpapps">All events</a>
                    <span class="count">(<span class="pending-count"><?=$counts->events_all?></span>)</span></a> |
                </li>
                <li class="moderated"><a <?php if ($filter_idx == 1) { echo 'class="current"'; } ?> href="?page=wpapps&amp;adm_filter=1">Pending 
                    <span class="count">(<span class="pending-count"><?=$counts->ideas_pending?></span>)</span></a> |
                </li>
            </ul>
        <?php

        //eval($this->get_template_eval("admin")); // eval is evil
    }

/*    function get_template_eval($what) {
        return 'echo <<<HTML'.PHP_EOL.$this->tpls->$what.PHP_EOL.'HTML;'.PHP_EOL;
    }*/

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