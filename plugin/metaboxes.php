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

class WPApps_Metaboxes {
    var $main;

    function __construct($main) {
        $this->main = $main;

        // Use nice custom meta boxes by HumanMade
        if (!defined('CMB_PATH'))
            require_once WPAPPS_PATH . "/cmb/custom-meta-boxes.php";

//        add_action('add_meta_boxes', [$this, 'add_meta_box']);
//        add_action('post_updated', [$this, 'save']);

        add_filter('cmb_meta_boxes', [$this, 'add_event_metaboxes']);
    }

    function add_event_metaboxes($meta_boxes) {
        $meta_boxes[] = [
            'title' => __('Information', 'wpapps'),
            'pages' => 'event',
            'fields' => [
                ['id' => 'logo', 'name' => __('Event Logo', 'wpapps'), 'type' => 'image', 'repeatable' => false],
                ['id' => 'when_start', 'name' => __('Event Start', 'wpapps'), 'type' => 'datetime_unix', 'repeatable' => false],
                ['id' => 'when_end', 'name' => __('Event End', 'wpapps'), 'type' => 'datetime_unix', 'repeatable' => false],
                ['id' => 'location', 'name' => __('Event Location', 'wpapps'), 'type' => 'textarea', 'repeatable' => false],
                ['id' => 'organizer', 'name' => __('Event Organizer', 'wpapps'), 'type' => 'textarea', 'repeatable' => false],
                ['id' => 'edition', 'name' => __('Edition', 'wpapps'), 'type' => 'text', 'repeatable' => false]
            ],
            'context' => 'side',
            'priority' => 'high'
        ];
        $meta_boxes[] = [
            'title' => __('Jury', 'wpapps'),
            'pages' => 'event',
            'fields' => [
                ['id' => 'jury', 'name' => __("Jury member", 'wpapps'), 'type' => 'text', 'repeatable' => true]
            ]
        ];
        $meta_boxes[] = [
            'title' => __('Awards', 'wpapps'),
            'pages' => 'event',
            'fields' => [
                ['id' => 'award', 'name' => __("Award", 'wpapps'), 'type' => 'group', 'repeatable' => true, 'fields' => [
                    ['id' => 'award-prize', 'name' => __("Prize", 'wpapps'), 'type' => 'text'],
                    ['id' => 'award-sponsor', 'name' => __("Sponsor", 'wpapps'), 'type' => 'text']
                ]]
            ]
        ];
        $meta_boxes[] = [
            'title' => __('Sponsors', 'wpapps'),
            'pages' => 'event',
            'fields' => [
                ['id' => 'sponsor', 'name' => __("Sponsor", 'wpapps'), 'type' => 'text', 'repeatable' => true]
            ]
        ];
        return $meta_boxes;
    }
}