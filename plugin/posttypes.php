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
                'name' => __('Events', 'wpapps'),
                'singular_name' => __('Event', 'wpapps'),
                'add_new' => __('Add New', 'wpapps'),
                'add_new_item' => __('Add New Event', 'wpapps'),
                'edit_item' => __('Edit Event', 'wpapps'),
                'new_item' => __('New Event', 'wpapps'),
                'all_items' => __('Events', 'wpapps'),
                'view_item' => __('View Event', 'wpapps'),
                'search_items' => __('Search Events', 'wpapps'),
                'not_found' =>  __('No events found', 'wpapps'),
                'not_found_in_trash' => __('No events found in Trash', 'wpapps'),
                'parent_item_colon' => '',
                'menu_name' => __('Events', 'wpapps')
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
            'map_meta_cap' => true,
            'capabilities' => ['read' => 'read_events']
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
                'name' => __('Ideas', 'wpapps'),
                'singular_name' => __('Idea', 'wpapps'),
                'add_new' => __('Add New', 'wpapps'),
                'add_new_item' => __('Add New Idea', 'wpapps'),
                'edit_item' => __('Edit Idea', 'wpapps'),
                'new_item' => __('New Idea', 'wpapps'),
                'all_items' => __('Ideas', 'wpapps'),
                'view_item' => __('View Idea', 'wpapps'),
                'search_items' => __('Search Ideas', 'wpapps'),
                'not_found' =>  __('No ideas found', 'wpapps'),
                'not_found_in_trash' => __('No ideas found in Trash', 'wpapps'),
                'parent_item_colon' => '',
                'menu_name' => __('Ideas', 'wpapps')
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
            'map_meta_cap' => true,
            'capabilities' => ['read' => 'read_events']
        ));
    }

    function register_app() {
        register_post_type("app", array(
            'labels' => array(
                'name' => __('Apps', 'wpapps'),
                'singular_name' => __('App', 'wpapps'),
                'add_new' => __('Add New', 'wpapps'),
                'add_new_item' => __('Add New App', 'wpapps'),
                'edit_item' => __('Edit App', 'wpapps'),
                'new_item' => __('New App', 'wpapps'),
                'all_items' => __('Apps', 'wpapps'),
                'view_item' => __('View App', 'wpapps'),
                'search_items' => __('Search Apps', 'wpapps'),
                'not_found' =>  __('No apps found', 'wpapps'),
                'not_found_in_trash' => __('No apps found in Trash', 'wpapps'),
                'parent_item_colon' => '',
                'menu_name' => __('Apps', 'wpapps')
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
            'map_meta_cap' => true,
            'capabilities' => ['read' => 'read_events' /*NOT read_event*/]
        ));
    }

}