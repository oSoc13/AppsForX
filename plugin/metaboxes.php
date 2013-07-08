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

    public function __construct($main) {
        $this->main = $main;

        // Use nice custom meta boxes by HumanMade
        if (!defined('CMB_PATH'))
            require_once WPAPPS_PATH . "/cmb/custom-meta-boxes.php";

//        add_action('load-post-new.php', ''
//        add_action('load-post.php',

        add_action('add_meta_boxes', [$this, 'add_meta_box']);
        add_action('post_updated', [$this, 'save']);

        add_filter('cmb_meta_boxes', [$this, 'add_event_metaboxes']);




        // browse action/filter tests
        function event_cpt_columns($columns) {
            unset($columns["date"]);
            $new_columns = array(
                'when' => "When",
            );
            return array_merge($columns, $new_columns);
        }

        function custom_columns($column, $post_id) {
            switch ($column) {
                case "when":
                    echo date('d M Y - H:i', get_post_meta($post_id, 'when_start', true));
                    break;
            }
        }

        add_action("manage_event_posts_custom_column", "custom_columns", 10, 2);
        add_filter('manage_event_posts_columns' , 'event_cpt_columns');
    }

    public function add_event_metaboxes($meta_boxes) {
        $meta_boxes[] = array(
            'title' => 'Information',
            'pages' => 'event',
            'fields' => [
                ['id' => 'field-12', 'name' => 'Event Logo', 'type' => 'image', 'repeatable' => false],
                ['id' => 'when_start', 'name' => 'Event Start', 'type' => 'datetime_unix'],
                ['id' => 'when_end', 'name' => 'Event End', 'type' => 'datetime_unix'],
                ['id' => 'field-20', 'name' => 'Event Location', 'type' => 'textarea']
            ],
            'context' => 'side',
            'priority' => 'high'
        );
        return $meta_boxes;
    }

    public function add_meta_box() {
        global $wp_meta_boxes;

//        remove_meta_box('postimagediv', 'event', 'side');
//        add_meta_box('postimagediv', __('Event logo', 'blaaabla'), 'post_thumbnail_meta_box', 'event', 'side', 'high');

//        add_meta_box(
//            'some_meta_box_name'
//            ,__( 'Some Meta Box Headline', "bla" )
//            ,array( &$this, 'render_meta_box_content' )
//            ,'event'
//            ,'advanced'
//            ,'high'
//        );
//
//        add_meta_box(
//            'some_meta_box_name_2'
//            ,__( 'Some Meta Box Headline2', "blbabababaa" )
//            ,array( &$this, 'render_meta_box_content' )
//            ,'event'
//            ,'side'
//            ,'high'
//        );

    }

    public function save( $post_id ) {
        // Check permissions first
        if (('page' == @$_POST['post_type'] && ! current_user_can( 'edit_page', $post_id ) ) ||
            ( ! current_user_can( 'edit_post', $post_id ) ) ||
            ( ! isset( $_POST['myplugin_noncename'] ) || ! wp_verify_nonce( $_POST['myplugin_noncename'], plugin_basename( __FILE__ ) ) )
        ) return;

        // Thirdly we can save the value to the database

        //if saving in a custom table, get post_ID
        $post_ID = $_POST['post_ID'];
        //sanitize user input
        $mydata = sanitize_text_field( $_POST['myplugin_new_field'] );

        // Do something with $mydata
        // either using
        if ( !add_post_meta( $post_ID, '_my_meta_value_key', $mydata, true ) ) {
            update_post_meta( $post_ID, '_my_meta_value_key', $mydata );
        }
        // or a custom table (see Further Reading section below)
    }


    /**
    * Render Meta Box content
    */
    public function render_meta_box_content( $post ) {
        wp_nonce_field(plugin_basename( __FILE__ ), 'wpapps_nonce');

        // The actual fields for data entry
        // Use get_post_meta to retrieve an existing value from the database and use the value for the form
        $value = get_post_meta( $post->ID, '_my_meta_value_key', true );
        echo '<label for="myplugin_new_field">';
            _e( 'Description for this field', 'myplugin_textdomain' );
            echo '</label> ';
        echo '<input type="text" id="myplugin_new_field" name="myplugin_new_field" value="'.esc_attr( $value ).'" size="25" />';
    }
}