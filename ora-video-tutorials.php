<?php
/*
Plugin Name: Ora Video Tutorials
Plugin URI: https://github.com/PressedSolutions/Ora-Wellness-Plugins
Description: Adds custom post type for video tutorials
Version: 1.1.1
Author: Andrew Minion/Pressed Solutions
Author URI: http://www.pressedsolutions.com
Text Domain: genesis
*/

if (!defined('ABSPATH')) {
    exit;
}

if ( ! function_exists('ora_video_tutorial') ) {

// Register Custom Post Type
function ora_video_tutorial() {

    $labels = array(
        'name'                  => _x( 'Video Tutorials', 'Post Type General Name', 'genesis' ),
        'singular_name'         => _x( 'Video Tutorial', 'Post Type Singular Name', 'genesis' ),
        'menu_name'             => __( 'Video Tutorials', 'genesis' ),
        'name_admin_bar'        => __( 'Video Tutorial', 'genesis' ),
        'parent_item_colon'     => __( 'Parent Video Tutorial:', 'genesis' ),
        'all_items'             => __( 'All Video Tutorials', 'genesis' ),
        'add_new_item'          => __( 'Add New Video Tutorial', 'genesis' ),
        'add_new'               => __( 'Add New', 'genesis' ),
        'new_item'              => __( 'New Item', 'genesis' ),
        'edit_item'             => __( 'Edit Item', 'genesis' ),
        'update_item'           => __( 'Update Video Tutorial', 'genesis' ),
        'view_item'             => __( 'View Video Tutorial', 'genesis' ),
        'search_items'          => __( 'Search Video Tutorial', 'genesis' ),
        'not_found'             => __( 'Not found', 'genesis' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'genesis' ),
        'items_list'            => __( 'Video Tutorials list', 'genesis' ),
        'items_list_navigation' => __( 'Video Tutorials list navigation', 'genesis' ),
        'filter_items_list'     => __( 'Filter video tutorials list', 'genesis' ),
    );
    $rewrite = array(
        'slug'                  => 'videos',
        'with_front'            => true,
        'pages'                 => true,
        'feeds'                 => true,
    );
    $args = array(
        'label'                 => __( 'Video Tutorial', 'genesis' ),
        'description'           => __( 'Video Tutorial', 'genesis' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'author', 'page-attributes', 'comments' ),
        'hierarchical'          => true,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-format-video',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => 'videos',
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'rewrite'               => $rewrite,
        'capability_type'       => 'page',
    );
    register_post_type( 'video_tutorial', $args );

}
add_action( 'init', 'ora_video_tutorial', 0 );

// flush rewrite rules on activation
function ora_video_flush_rewrite_rules() {
    ora_video_tutorial();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'ora_video_flush_rewrite_rules' );

function ora_video_tutorial_order( $query ) {
    if ( $query->is_main_query() && !is_admin() ) {
        $query->set( 'orderby', array( 'menu_order' => 'ASC', 'date' => 'DESC' ) );
    }
}
add_action( 'pre_get_posts' , 'ora_video_tutorial_order' );

}
