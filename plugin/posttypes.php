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

class WPApps_Posttypes {
    var $main;

    function __construct($main) {
        $this->main = $main;

        add_action('init', [$this, "register_event"]);
        add_action('init', [$this, "register_idea"]);
        add_action('init', [$this, "register_app"]);

        add_action("manage_event_posts_custom_column", [$this, 'custom_event_column'], 10, 2);
        add_filter('manage_event_posts_columns' , [$this, 'custom_event_columns']);
    }

    ### Events

    function register_event() {
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
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => false,
            'query_var' => true,
            'rewrite' => array( 'slug' => 'event' ),
            'capability_type' => 'event',
            'has_archive' => true,
            'hierarchical' => false,
            'supports' => array( 'title', 'editor', 'comments' ),
            'map_meta_cap' => true
        ));
    }

    // set browsable event fields, handle display
    function custom_event_columns($columns) {
        unset($columns["date"]);
        $columns['when'] = "When";
        return $columns;
    }

    function custom_event_column($column, $post_id) {
        switch ($column) {
            case "when":
                echo date('d M Y - H:i', get_post_meta($post_id, 'when_start', true));
                break;
        }
    }

    ### Apps(+Concepts)

    function register_idea() {
        register_post_type("idea", array(
            'labels' => array(
                'name' => 'Ideas',
                'singular_name' => 'Idea',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Idea',
                'edit_item' => 'Edit Idea',
                'new_item' => 'New Idea',
                'all_items' => 'Ideas',
                'view_item' => 'View Idea',
                'search_items' => 'Search Ideas',
                'not_found' =>  'No ideas found',
                'not_found_in_trash' => 'No ideas found in Trash',
                'parent_item_colon' => '',
                'menu_name' => 'Ideas'
            ),
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => false,
            'query_var' => true,
            'rewrite' => array( 'slug' => 'idea' ),
            'capability_type' => 'idea',
            'has_archive' => true,
            'hierarchical' => false,
            'supports' => array( 'title', 'editor', 'comments' ),
            // new since 3.5 http://codex.wordpress.org/Function_Reference/register_post_type
            'map_meta_cap' => true
        ));
    }

    function register_app() {
        register_post_type("app", array(
            'labels' => array(
                'name' => 'Apps',
                'singular_name' => 'App',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New App',
                'edit_item' => 'Edit App',
                'new_item' => 'New App',
                'all_items' => 'Apps',
                'view_item' => 'View App',
                'search_items' => 'Search Apps',
                'not_found' =>  'No apps found',
                'not_found_in_trash' => 'No apps found in Trash',
                'parent_item_colon' => '',
                'menu_name' => 'Apps'
            ),
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => false,
            'query_var' => true,
            'rewrite' => array( 'slug' => 'app' ),
            'capability_type' => 'app',
            'has_archive' => true,
            'hierarchical' => false,
            'supports' => array( 'title', 'editor', 'comments' ),
            'map_meta_cap' => true
        ));
    }

}