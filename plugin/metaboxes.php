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
            'title' => 'Information',
            'pages' => 'event',
            'fields' => [
                ['id' => 'field-12', 'name' => 'Event Logo', 'type' => 'image', 'repeatable' => false],
                ['id' => 'when_start', 'name' => 'Event Start', 'type' => 'datetime_unix'],
                ['id' => 'when_end', 'name' => 'Event End', 'type' => 'datetime_unix'],
                ['id' => 'field-20', 'name' => 'Event Location', 'type' => 'textarea'],
                ['id' => 'edition', 'name' => 'Edition', 'type' => 'text']
            ],
            'context' => 'side',
            'priority' => 'high'
        ];
        $meta_boxes[] = [
            'title' => 'Jury',
            'pages' => 'event',
            'fields' => [
                ['id' => 'field-13', 'name' => "Jurylid", 'type' => 'text', 'repeatable' => true]
            ]
        ];
        return $meta_boxes;
    }
}