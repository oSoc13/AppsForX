<?php

/*
 * Plugin Name: Apps4X
 * Plugin URI: https://github.com/oSoc13/AppsForX
 * Description: Adding events, apps and ideas to a WordPress blog
 * Version: 1.0
 * Author: Cedric Van Bockhaven
 * Author URI: http://ce3c.be
 * Text Domain: wpapps

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
 *
 * Attributions: See README or GitHub page for the full attributions list
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

        $this->setup_debug();
        $this->setup_translations();
        $this->setup_options();

        $this->setup_relationships();
        $this->setup_template();

        require_once WPAPPS_PATH . '/database.php'; // Purge?
        require_once WPAPPS_PATH . '/posttypes.php';
        require_once WPAPPS_PATH . '/metaboxes.php';
        $this->setup_roles();
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
                $types = ["event", "idea", "app"];
                $ptype = @($_REQUEST["post_type"] ?: get_post_type(get_the_ID()));

                if (in_array($ptype, $types)) {
                    $parent_file = "wpapps";
                    $submenu_file = "edit.php?post_type=$ptype";
                }
            });

            add_action('admin_menu', function() {
                add_menu_page("Apps4X", "Apps4X", "edit_ideas", "wpapps", [$this, "page_overview"], WPAPPS_URL . "/style/calendar16.png", (string)(27+M_PI)); // rule of pi

                add_submenu_page("wpapps", "Overview", "Overview", "edit_ideas", "wpapps", [$this, "page_overview"]); // overwrite menu title
                add_submenu_page("wpapps", "Events", "Events", "edit_others_ideas", "edit.php?post_type=event");
                add_submenu_page("wpapps", "Ideas", "Ideas", "edit_ideas", "edit.php?post_type=idea");
                add_submenu_page("wpapps", "Apps", "Apps", "edit_others_ideas", "edit.php?post_type=app");
            });

            // Add admin css
            add_action('admin_init', function() {
                wp_register_style('wpapps-admin', WPAPPS_URL . '/style/wpapps-admin.css', [],  self::WPAPPS_VERSION);
                wp_enqueue_style('wpapps-admin');
            });
        }
        else { // frontend
            add_action('wp_print_styles', function() {
                wp_register_style('wpapps', WPAPPS_URL.'/style/wpapps.css');
                wp_enqueue_style('wpapps');
            });
        }
    }

    private function setup_options() {
        $defaults = [
            "plugin_version" => 0
        ];

        $this->options = get_option("wpapps_options", $defaults);
    }

    private function setup_debug() {
        restore_error_handler();
        error_reporting(E_ALL);
        ini_set('error_reporting', E_ALL);
        ini_set('html_errors',TRUE);
        ini_set('display_errors',TRUE);
    }

    private function setup_translations() {
        add_action('plugins_loaded', function() {
            load_plugin_textdomain("wpapps", false, dirname(plugin_basename(__FILE__)) . "lang");
        });
    }

    // copy all files over to the themes directory on activation
    // also remove them on plugin deactivation... if they weren't modified by the user
    // Future: perhaps use symlinks... linux|windows >6.1
    private function setup_template() {
        $tpl_source = WPAPPS_PATH . '/tpls';
        $tpl_dest = get_template_directory();

        // A function declared in a private function inside a class, becomes a global function...
        $foreach_tplfile = function($tpl_source, $tpl_dest, $func) {
            foreach (glob("$tpl_source/*.php", GLOB_NOSORT) as $srcfile) {
                $destfile = $tpl_dest . '/' . basename($srcfile);
                $func($srcfile, $destfile);
            }
        };

        register_activation_hook(__FILE__, function() use ($foreach_tplfile, $tpl_source, $tpl_dest) {
            $foreach_tplfile($tpl_source, $tpl_dest, function($src, $dest) {
                if (!file_exists($dest)) {
                    if (!@copy($src, $dest)) {
                        set_error_handler(function($a,$b) { die($b); });
                        trigger_error("<strong>Couldn't copy page template.</strong><br />Please make sure that the write permissions for wp-content are correct.", E_USER_ERROR);
                        restore_error_handler();
                    }
                }
            });
        });

        register_deactivation_hook(__FILE__, function() use ($foreach_tplfile, $tpl_source, $tpl_dest) {
            $foreach_tplfile($tpl_source, $tpl_dest, function($src, $dest) {
                @rename($dest, $dest.".old");
                // if the file wasn't modified, we can safely delete it without the owner missing his file...
                if (@md5_file($src) == @md5_file($dest.".old")) {
                    @unlink($dest.".old");
                }
            });
        });
    }

    private function setup_relationships()
    {
        // Only include P2P if it isn't being used by the site owner yet
        if (!defined("P2P_TEXTDOMAIN"))
            require WPAPPS_PATH . '/posts-to-posts/posts-to-posts.php';

        add_action('p2p_init', function () {
            p2p_register_connection_type([
                'name' => 'events_to_ideas',
                'from' => 'event',
                'to' => 'idea'
            ]);
        });
    }

    private function setup_roles() // Move to posttypes.php? Must be hooked into activation hook tho
    {
        // http://stackoverflow.com/a/16656057
        // WP3.5+ in combo with the meta_cap of posttypes

        // http://wordpress.stackexchange.com/a/88397
        // "Turns out it's a real bad idea to map your own meta capabilities."

        // do this as activation hook, since it will be added to the database
        remove_role('idea_submitter');
        remove_role('wpapps_submitter');

        add_role('wpapps_submitter', 'Submitter', ["read" => true]);

        $allcaps = [];

        $roles = [
            "subscriber" => [],
            "contributor" => [
                'read_idea' => true,
                'edit_ideas' => true,
                'delete_ideas' => true,
                'edit_idea' => true,
                'delete_idea' => true,
                'edit_published_ideas' => true,
                'delete_published_ideas' => true
            ],
            "author" => [],
            "wpapps_submitter" => [],
            "editor" => [
                'edit_others_ideas' => true,
                'delete_private_ideas' => true,
                'delete_others_ideas' => true,
                'edit_private_ideas' => true,
            ],
            "administrator" => []
        ];

        foreach($roles as $role => $caps) {
            $allcaps = array_merge($allcaps, $caps);

            foreach ($allcaps as $cap => $val) {
                get_role($role)->add_cap($cap, $val);
            }
        }


    }
}

new WPApps;
