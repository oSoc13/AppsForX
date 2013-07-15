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
        add_filter('cmb_meta_boxes', [$this, 'add_idea_metaboxes']);
    }

    function add_event_metaboxes($meta_boxes) {
        $meta_boxes[] = [
            'title' => _x('Information', 'event-edit', WPAPPS_TRANS),
            'pages' => 'event',
            'fields' => [
                ['id' => 'logo', 'name' => __('Event Logo', WPAPPS_TRANS), 'type' => 'image', 'repeatable' => false],
                ['id' => 'when_start', 'name' => __('Event Start', WPAPPS_TRANS), 'type' => 'datetime_unix', 'repeatable' => false],
                ['id' => 'when_end', 'name' => __('Event End', WPAPPS_TRANS), 'type' => 'datetime_unix', 'repeatable' => false],
                ['id' => 'location', 'name' => __('Event Location', WPAPPS_TRANS), 'type' => 'textarea', 'repeatable' => false],
                ['id' => 'organizer', 'name' => __('Event Organizer', WPAPPS_TRANS), 'type' => 'textarea', 'repeatable' => false],
                ['id' => 'edition', 'name' => __('Edition', WPAPPS_TRANS), 'type' => 'text', 'repeatable' => false]
            ],
            'context' => 'side',
            'priority' => 'high'
        ];
        $meta_boxes[] = [
            'title' => __('Jury', WPAPPS_TRANS),
            'pages' => 'event',
            'fields' => [
                ['id' => 'jury', 'name' => __("Jury member", WPAPPS_TRANS), 'type' => 'text', 'repeatable' => true]
            ]
        ];
        $meta_boxes[] = [
            'title' => __('Awards', WPAPPS_TRANS),
            'pages' => 'event',
            'fields' => [
                ['id' => 'award', 'name' => __("Award", WPAPPS_TRANS), 'type' => 'group', 'repeatable' => true, 'fields' => [
                    ['id' => 'award-prize', 'name' => __("Prize", WPAPPS_TRANS), 'type' => 'text'],
                    ['id' => 'award-sponsor', 'name' => __("Sponsor", WPAPPS_TRANS), 'type' => 'text']
                ]]
            ]
        ];
        $meta_boxes[] = [
            'title' => __('Sponsors', WPAPPS_TRANS),
            'pages' => 'event',
            'fields' => [
                ['id' => 'sponsor', 'name' => __("Sponsor", WPAPPS_TRANS), 'type' => 'text', 'repeatable' => true]
            ]
        ];
        return $meta_boxes;
    }

    function add_idea_metaboxes($meta_boxes) {
        $meta_boxes[] = [
            'title' => _x('Information', 'idea-edit', WPAPPS_TRANS),
            'pages' => 'idea',
            'fields' => [
                ['id' => 'summary', 'name' => __("Summary", WPAPPS_TRANS), 'type' => 'textarea'],
                ['id' => 'theme', 'name' => __("Theme", WPAPPS_TRANS), 'type' => 'select', 'options' => [
                    '' => _x('Select theme', 'theme', WPAPPS_TRANS),
                    'theme-administration' => _x('Public administration & policy', 'theme', WPAPPS_TRANS),
                    'theme-population' => _x('Population', 'theme', WPAPPS_TRANS),
                    'theme-culture' => _x('Culture/Sport/Leisure time', 'theme', WPAPPS_TRANS),
                    'theme-territory' => _x('Territory', 'theme', WPAPPS_TRANS),
                    'theme-health' => _x('Health', 'theme', WPAPPS_TRANS),
                    'theme-infrastructure' => _x('Infrastructure', 'theme', WPAPPS_TRANS),
                    'theme-audience' => _x('Audience (Youth/Adult/Senior)', 'theme', WPAPPS_TRANS),
                    'theme-environment' => _x('Environment & Nature', 'theme', WPAPPS_TRANS),
                    'theme-education' => _x('Education & Lifelong learning', 'theme', WPAPPS_TRANS),
                    'theme-tourism' => _x('Tourism', 'theme', WPAPPS_TRANS),
                    'theme-safety' => _x('Safety', 'theme', WPAPPS_TRANS),
                    'theme-welfare' => _x('Welfare', 'theme', WPAPPS_TRANS),
                    'theme-economy' => _x('Work & Economy', 'theme', WPAPPS_TRANS),
                    'theme-lifehome' => _x('Life/Home', 'theme', WPAPPS_TRANS)
                ]],
                ['id' => 'homepage', 'name' => _x("Homepage", "idea-edit", WPAPPS_TRANS), 'type' => 'text_url'],
                ['id' => 'language', 'name' => _x("Language", "idea-edit", WPAPPS_TRANS), 'type' => 'text', 'desc' => __("The language used to describe the idea.<br />Eg. 'Dutch'", WPAPPS_TRANS)]
            ],
            'context' => 'side',
            'priority' => 'high'
        ];
        $meta_boxes[] = [
            'title' => _x('People', 'idea-edit', WPAPPS_TRANS),
            'pages' => 'idea',
            'fields' => [
                ['id' => 'conceivers', 'name' => 'Conceivers', 'type' => 'text', 'repeatable' => true],
                ['id' => 'contact', 'name' => 'Contact', 'type' => 'text'] // should have email/phone number? -> make abstract
            ]
        ];
        return $meta_boxes;
    }
}