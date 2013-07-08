<?php

/*
 * Plugin Name: Apps
 * Plugin URI: http://wpapp
 * Description: Adding apps and ideas to a WordPress blog
 * Version: 1.0
 * Author: Cedric Van Bockhaven
 * Author URI: http://ce3c.be
 * License: Other

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

    var $dbtable;
    var $tpls;
    var $options;

    public function __construct() {
        global $wpdb;

        define('IN_WPAPPS', 1);
        define('WPAPPS_URL', plugin_dir_url(__FILE__));
        define('WPAPPS_PATH', plugin_dir_path(__FILE__));

        $this->dbtable = $wpdb->prefix . self::WPAPPS_DBTABLE;
        $this->get_options();
        $this->create_update_db_table();

        //debug
        restore_error_handler();
        error_reporting(E_ALL);
        ini_set('error_reporting', E_ALL);
        ini_set('html_errors',TRUE);
        ini_set('display_errors',TRUE);

        add_action('init', function() {
            register_post_type("event", array(
                'labels' => array(
                    'name' => 'Events',
                    'singular_name' => 'Event',
                    'add_new' => 'Add New',
                    'add_new_item' => 'Add New Event',
                    'edit_item' => 'Edit Event',
                    'new_item' => 'New Event',
                    'all_items' => 'Events',
                    'view_item' => 'View Event',
                    'search_items' => 'Search Events',
                    'not_found' =>  'No events found',
                    'not_found_in_trash' => 'No events found in Trash',
                    'parent_item_colon' => '',
                    'menu_name' => 'Events'
                ),
                'menu_icon' => WPAPPS_PATH . "/events_icon.png",
                'public' => true,
                'publicly_queryable' => true,
                'show_ui' => true,
                'show_in_menu' => false,
                'query_var' => true,
                'rewrite' => array( 'slug' => 'event' ),
                'capability_type' => 'post',
                'has_archive' => true,
                'hierarchical' => false,
                'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
            ));
        });

        add_action('add_meta_boxes', function() {
            function product_price_box_content( $post ) {
                wp_nonce_field( plugin_basename( __FILE__ ), 'product_price_box_content_nonce' );
                echo '';
                echo '';
            }

            add_meta_box(
                'event_meta_box',
                __( 'Product Price', 'myplugin_textdomain' ),
                'product_price_box_content',
                'event',
                'side',
                'high'
            );
        });

        if (is_admin()) { // backend
            // Add admin menu hook

            // maintain highlighting, the hard way
            // not sure if this is the correct action to hook into, but it seems to work
            add_action('admin_head', function() {
                global $submenu_file, $parent_file;
                if (@$_GET["post_type"] == "event" || get_post_type(get_the_ID()) == "event") {
                    $parent_file = "wpapps";
                    $submenu_file = "edit.php?post_type=event";
                }
            });

            add_action('admin_menu', function() {
                global $submenu;
                add_menu_page("Apps4X", "Apps4X", "edit_others_posts", "wpapps", array(&$this, "page_overview"), null, (string)(27+M_PI)); // rule of pi

                add_submenu_page("wpapps", "Overview", "Overview", "edit_others_posts", "wpapps", array(&$this, "page_overview")); // overwrite menu title
                add_submenu_page("wpapps", "Events", "Events", "edit_others_posts", "edit.php?post_type=event");
                add_submenu_page("wpapps", "App concept ideas", "Ideas", "edit_others_posts", "wpapps_ideas", array(&$this, "page_ideas"));
            });

            // Add admin css
            add_action('admin_init', function() {
                wp_register_style('wpapps-admin', WPAPPS_URL . '/wpapps-admin.css' , array(),  self::WPAPPS_VERSION);
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

    function page_overview() {
        global $wpdb, $wp_query;


//        $counts = $wpdb->get_row("SELECT
//            (SELECT COUNT(*) FROM {$this->dbtable}_events) AS events_all,
//            (SELECT COUNT(*) FROM {$this->dbtable}_ideas) AS ideas_all,
//            (SELECT COUNT(*) FROM {$this->dbtable}_ideas WHERE published = 0) AS ideas_pending
//        ");
//
//        $statuses = ["All events", /*"All ideas",*/ "Pending ideas"];
//        $filter_idx = @$statuses[$_GET['adm_filter']] ? (int)$_GET['adm_filter'] : 0;
//
//        require_once(dirname(__FILE__)."/events_overview.tbl.php");
//        $testListTable = new WPApps_EventsOverviewTable();
//        $testListTable->prepare_items();
//
//        include(dirname(__FILE__).'/events_overview.tpl.php');
    }

    function page_events() {
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

    function create_update_db_table() {
        if ($this->options['plugin_version'] == self::WPAPPS_VERSION) return;

        require_once( ABSPATH . '/wp-admin/includes/upgrade.php');

        $sqltext = file_get_contents(dirname(__FILE__)."/wpapps.sql");
        $sqleval = eval('return <<<SQL'.PHP_EOL.$sqltext.PHP_EOL.'SQL;'.PHP_EOL); // eval is evil

        $this->options["plugin_version"] =  self::WPAPPS_VERSION;
        update_option("wpapps_options", $this->options);

        dbDelta($sqleval);
    }

    function get_options() {
        $defaults = [
            "plugin_version" => 0
        ];

        $this->options = get_option("wpapps_options", $defaults);
    }
}

$WPApps = new WPApps();