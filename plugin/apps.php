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

    // setvars
    var $options;
    // classvars
    var $posttypes, $metaboxes;

    function __construct() {
        define('IN_WPAPPS', 1);
        define('WPAPPS_DEBUG', false);
        define('WPAPPS_URL', plugin_dir_url(__FILE__));
        define('WPAPPS_PATH', plugin_dir_path(__FILE__));
        define('WPAPPS_TRANS', 'wpapps');

        $this->setup_debug();
        $this->setup_options(); // nominated for removal
        $this->setup_translations();
        $this->setup_roles();
        $this->setup_relationships();
        $this->setup_template();

        require_once WPAPPS_PATH . '/posttypes.php';
        require_once WPAPPS_PATH . '/metaboxes.php';

        $this->posttypes = new WPApps_Posttypes($this);
        $this->metaboxes = new WPApps_Metaboxes($this);

        $this->setup_hooks();
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
                add_menu_page(__("Apps4X", WPAPPS_TRANS), __("Apps4X", WPAPPS_TRANS), "edit_ideas", "wpapps", [$this, "page_overview"], WPAPPS_URL . "/style/calendar16.png", (string)(27+M_PI)); // rule of pi

                add_submenu_page("wpapps", __("Overview", WPAPPS_TRANS), __("Overview", WPAPPS_TRANS), "edit_ideas", "wpapps", [$this, "page_overview"]); // overwrite menu title
                add_submenu_page("wpapps", __("Events", WPAPPS_TRANS), __("Events", WPAPPS_TRANS), "edit_events", "edit.php?post_type=event");
                add_submenu_page("wpapps", __("Ideas", WPAPPS_TRANS), __("Ideas", WPAPPS_TRANS), "edit_ideas", "edit.php?post_type=idea");
                add_submenu_page("wpapps", __("Apps", WPAPPS_TRANS), __("Apps", WPAPPS_TRANS), "edit_apps", "edit.php?post_type=app");
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

    public function page_overview() {} // ToDo

    private function setup_options() {
        $defaults = [
            "plugin_version" => 0
        ];

        $this->options = get_option("wpapps_options", $defaults);
    }

    private function setup_translations() {
        add_action('plugins_loaded', function() {
            load_plugin_textdomain("wpapps", false, dirname(plugin_basename(__FILE__)) . "/lang/");
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
                if (!file_exists($dest) && !@copy($src, $dest))
                    wpapps_error(__("<strong>Couldn't copy page template.</strong><br />Please make sure that the write permissions for wp-content are correct.", WPAPPS_TRANS));
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
        // Also check if git was properly cloned
        $p2p = WPAPPS_PATH . '/posts-to-posts/posts-to-posts.php';

        register_activation_hook(__FILE__, function() use ($p2p) {
            if (!file_exists($p2p))
                wpapps_error(__("Some files appear to be missing. Git has to be cloned recursively!", WPAPPS_TRANS));

            $pfile = WPAPPS_PATH . '/posts-to-posts/core/side-post.php';
            if (!is_writable($pfile))
                wpapps_error(sprintf(__("Can't write to %s which has to be patched. Apply patch manually or make the file writable.", WPAPPS_TRANS), $pfile));
            else
                file_put_contents($pfile, str_replace("->edit_posts", "->read", file_get_contents($pfile)));
        });

        
        if (!defined("P2P_TEXTDOMAIN"))
            require $p2p;

        add_action('p2p_init', function () {
            p2p_register_connection_type([
                'name' => 'events_to_ideas',
                'from' => 'event',
                'to' => 'idea',
                'can_create_post' => current_user_can("edit_others_ideas") // edit_events?
            ]);

            p2p_register_connection_type([
                'name' => 'ideas_to_apps',
                'from' => 'idea',
                'to' => 'app',
                'can_create_post' => current_user_can("edit_apps")
            ]);
        });
    }

    private function setup_roles() // Move to posttypes.php? Must be hooked into activation hook tho
    {
        // WP3.5+ only
        // http://stackoverflow.com/a/16656057
        // http://wordpress.stackexchange.com/a/88397
        register_activation_hook(__FILE__, function () {
            add_role('wpapps_submitter', 'Submitter', ["read" => true]);

            $allcaps = [];
            $roles = [
                "subscriber" => [],
                "contributor" => [
                    'read_idea' => true,
                    'read_ideas' => true,
                    'edit_ideas' => true,
                    'delete_ideas' => true,
                    'edit_idea' => true,
                    'delete_idea' => true,
                    'edit_published_ideas' => true,
                    'delete_published_ideas' => true,

                    'read_app' => true,
                    'read_apps' => true, // magic
                    'edit_apps' => true,
                    'delete_apps' => true,
                    'edit_app' => true,
                    'delete_app' => true,
                    'edit_published_apps' => true,
                    'delete_published_apps' => true,

                    'read_event' => true,
                    'read_events' => true,
                ],
                "author" => [],
                "wpapps_submitter" => [],
                "editor" => [
                    'edit_others_ideas' => true,
                    'delete_private_ideas' => true,
                    'delete_others_ideas' => true,
                    'edit_private_ideas' => true,
                    'publish_ideas' => true,

                    'edit_others_apps' => true,
                    'delete_private_apps' => true,
                    'delete_others_apps' => true,
                    'edit_private_apps' => true,
                    'publish_apps' => true,

                    'edit_events' => true,
                    'delete_events' => true,
                    'edit_event' => true,
                    'delete_event' => true,
                    'edit_published_events' => true,
                    'delete_published_events' => true,
                    'edit_others_events' => true,
                    'delete_private_events' => true,
                    'delete_others_events' => true,
                    'edit_private_events' => true,
                    'publish_events' => true,
                ],
                "administrator" => []
            ];

            foreach($roles as $role => $caps) {
                $allcaps = array_merge($allcaps, $caps);

                foreach ($allcaps as $cap => $val) {
                    get_role($role)->add_cap($cap, $val);
                }
            }
        });

        register_deactivation_hook(__FILE__, function() {
            remove_role('wpapps_submitter');
        });

        // Dirty hack to work around the edit_posts capability
        // When we're on post-new.php, we manually reset the edit.php permissions
        // that way, edit.php won't show up in the menu, but we'll be able to access it with Submitter permissions
        add_filter('user_has_cap', function ($allcaps, $cap, $args) {
            global $_wp_menu_nopriv, $_wp_submenu_nopriv;
            //print_r(debug_backtrace());
            if (@$cap[0] == "edit_posts" && (@$allcaps["edit_ideas"] || @$allcaps["edit_apps"]))
                unset($_wp_menu_nopriv["edit.php"], $_wp_submenu_nopriv["edit.php"]);

            return $allcaps;
        }, 10, 3);
    }

    private function setup_debug() {
        if (WPAPPS_DEBUG) {
            restore_error_handler();
            error_reporting(E_ALL);
            ini_set('error_reporting', E_ALL);
            ini_set('html_errors',TRUE);
            ini_set('display_errors',TRUE);
        }
    }

    public function wpapps_error($err) {
        set_error_handler(function($a,$b) { die($b); });
        trigger_error($err, E_USER_ERROR);
        restore_error_handler();
    }
}

new WPApps; // 300 lines!
