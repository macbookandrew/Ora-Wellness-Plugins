<?php
/*
Plugin Name: Ora Support Staff
Plugin URI: https://github.com/macbookandrew/Ora-Wellness-Plugins
Description: Adds custom post type for support staff
Version: 1.1
Author: Andrew Minion/Pressed Solutions
Author URI: http://www.pressedsolutions.com
Text Domain: genesis
*/

if (!defined('ABSPATH')) {
    exit;
}

// register custom post type
if ( ! function_exists('ora_support_staff') ) {

// Register Custom Post Type
function ora_support_staff() {

    $labels = array(
        'name'                  => _x( 'Support Staff', 'Post Type General Name', 'genesis' ),
        'singular_name'         => _x( 'Support Staff', 'Post Type Singular Name', 'genesis' ),
        'menu_name'             => __( 'Support Staff', 'genesis' ),
        'name_admin_bar'        => __( 'Support Staff', 'genesis' ),
        'all_items'             => __( 'All Support Staff', 'genesis' ),
        'add_new_item'          => __( 'Add New Support Staff Member', 'genesis' ),
        'add_new'               => __( 'Add New', 'genesis' ),
        'new_item'              => __( 'New Support Staff Member', 'genesis' ),
        'edit_item'             => __( 'Edit Support Staff Member', 'genesis' ),
        'update_item'           => __( 'Update Support Staff', 'genesis' ),
        'view_item'             => __( 'View Support Staff', 'genesis' ),
        'search_items'          => __( 'Search Support Staff', 'genesis' ),
        'not_found'             => __( 'Not found', 'genesis' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'genesis' ),
        'items_list'            => __( 'Support staff list', 'genesis' ),
        'items_list_navigation' => __( 'Support staff list navigation', 'genesis' ),
        'filter_items_list'     => __( 'Filter support staff list', 'genesis' ),
    );
    $args = array(
        'label'                 => __( 'Support Staff', 'genesis' ),
        'description'           => __( 'Support Staff', 'genesis' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'author', ),
        'hierarchical'          => true,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-businessman',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false,
        'capability_type'       => 'page',
    );
    register_post_type( 'support_staff', $args );

}
add_action( 'init', 'ora_support_staff', 0 );

// flush rewrite rules on activation
function ora_support_staff_flush_rewrite_rules() {
    ora_support_staff();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'ora_support_staff_flush_rewrite_rules' );

function ora_support_staff_order( $query ) {
    if ( $query->is_main_query() && !is_admin() ) {
        $query->set( 'orderby', array( 'title' => 'ASC', ) );
    }
}
add_action( 'pre_get_posts' , 'ora_support_staff_order' );

}

// register widget
class OraSupportStaffWidget extends WP_Widget {

    function __construct() {
        $widget_info = array(
            'classname'     => 'ora-support-staff',
            'description'   => 'Displays a list of support staff',
        );
        parent::__construct( false, 'Ora Support Staff', $widget_info );
    }

    function widget( $args, $instance ) {
        // WP_Query arguments
        $support_staff_args = array(
            'post_type'              => array( 'support_staff' ),
            'posts_per_page'         => -1,
            'orderby'                => 'title',
            'order'                  => 'ASC',
        );

        // The Query
        $support_staff_query = new WP_Query( $support_staff_args );

        // The Loop
        if ( $support_staff_query->have_posts() ) {
            echo '<aside class="ora-support-staff widget">
            <h6>Our Support Staff</h6>';
            echo $args['before_widget'];
            while ( $support_staff_query->have_posts() ) {
                $support_staff_query->the_post();

                echo '<figure class="staff-member">';
                // post thumbnail
                if ( has_post_thumbnail() ) {
                    the_post_thumbnail( array( 120, 120 ), array( 'class' => 'testimonial-thumb' ) );
                }

                // content
                echo '<figcaption class="name"><strong>' . get_the_title() . '</strong></figcaption>';

                echo '</figure>';
            }
            echo $args['after_widget'];
            echo '</aside>';
        }

        // Restore original Post Data
        wp_reset_postdata();
    }

    function form( $instance ) {
    }

    function update( $new_instance, $old_instance ) {
    }
}

function ora_support_staff_register_widget() {
    register_widget( 'OraSupportStaffWidget' );
}
add_action( 'widgets_init', 'ora_support_staff_register_widget' );
