<?php
/*
Plugin Name: Ora Membership Products
Plugin URI: https://gist.github.com/macbookandrew/92f01a1be124cd2678f0
Description: Adds custom post type for membership products
Version: 1.1.2
Author: Andrew Minion/Pressed Solutions
Author URI: http://www.pressedsolutions.com
Text Domain: genesis
*/

if (!defined('ABSPATH')) {
    exit;
}

define( 'PARENT_SLUG', 'membership-home/' );

if ( ! function_exists('ora_ebook') ) {

// Register Custom Post Type
function ora_ebook() {

    $labels = array(
        'name'                  => _x( 'Ebooks', 'Post Type General Name', 'genesis' ),
        'singular_name'         => _x( 'Ebook', 'Post Type Singular Name', 'genesis' ),
        'menu_name'             => __( 'Ebooks', 'genesis' ),
        'name_admin_bar'        => __( 'Ebook', 'genesis' ),
        'parent_item_colon'     => __( 'Parent Ebook:', 'genesis' ),
        'all_items'             => __( 'All Ebooks', 'genesis' ),
        'add_new_item'          => __( 'Add New Ebook', 'genesis' ),
        'add_new'               => __( 'Add New', 'genesis' ),
        'new_item'              => __( 'New Ebook', 'genesis' ),
        'edit_item'             => __( 'Edit Ebook', 'genesis' ),
        'update_item'           => __( 'Update Ebook', 'genesis' ),
        'view_item'             => __( 'View Ebook', 'genesis' ),
        'search_items'          => __( 'Search Ebook', 'genesis' ),
        'not_found'             => __( 'Not found', 'genesis' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'genesis' ),
        'items_list'            => __( 'Ebooks list', 'genesis' ),
        'items_list_navigation' => __( 'Ebooks list navigation', 'genesis' ),
        'filter_items_list'     => __( 'Filter ebook list', 'genesis' ),
    );
    $rewrite = array(
        'slug'                  => PARENT_SLUG . 'ebooks',
        'with_front'            => true,
        'pages'                 => true,
        'feeds'                 => true,
    );
    $args = array(
        'label'                 => __( 'Ebook', 'genesis' ),
        'description'           => __( 'Ebook', 'genesis' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'author', ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-book',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => PARENT_SLUG . 'ebooks',
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'rewrite'               => $rewrite,
        'capability_type'       => 'page',
    );
    register_post_type( 'ebook', $args );

}
add_action( 'init', 'ora_ebook', 0 );

}

if ( ! function_exists('ora_expert_interview') ) {

// Register Custom Post Type
function ora_expert_interview() {

    $labels = array(
        'name'                  => _x( 'Expert Interviews', 'Post Type General Name', 'genesis' ),
        'singular_name'         => _x( 'Expert Interview', 'Post Type Singular Name', 'genesis' ),
        'menu_name'             => __( 'Expert Interviews', 'genesis' ),
        'name_admin_bar'        => __( 'Expert Interview', 'genesis' ),
        'parent_item_colon'     => __( 'Parent Expert Interview:', 'genesis' ),
        'all_items'             => __( 'All Expert Interviews', 'genesis' ),
        'add_new_item'          => __( 'Add New Expert Interview', 'genesis' ),
        'add_new'               => __( 'Add New', 'genesis' ),
        'new_item'              => __( 'New Expert Interview', 'genesis' ),
        'edit_item'             => __( 'Edit Expert Interview', 'genesis' ),
        'update_item'           => __( 'Update Expert Interview', 'genesis' ),
        'view_item'             => __( 'View Expert Interview', 'genesis' ),
        'search_items'          => __( 'Search Expert Interview', 'genesis' ),
        'not_found'             => __( 'Not found', 'genesis' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'genesis' ),
        'items_list'            => __( 'Expert Interviews list', 'genesis' ),
        'items_list_navigation' => __( 'Expert Interviews list navigation', 'genesis' ),
        'filter_items_list'     => __( 'Filter expert interview list', 'genesis' ),
    );
    $rewrite = array(
        'slug'                  => PARENT_SLUG . 'expert-interviews',
        'with_front'            => true,
        'pages'                 => true,
        'feeds'                 => true,
    );
    $args = array(
        'label'                 => __( 'Expert Interview', 'genesis' ),
        'description'           => __( 'Expert Interview', 'genesis' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'author', ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-playlist-video',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => PARENT_SLUG . 'expert-interviews',
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'rewrite'               => $rewrite,
        'capability_type'       => 'page',
    );
    register_post_type( 'expert_interview', $args );

}
add_action( 'init', 'ora_expert_interview', 0 );
}

if ( ! function_exists('ora_summit_interview') ) {

// Register Custom Post Type
function ora_summit_interview() {

    $labels = array(
        'name'                  => _x( 'Summit Interviews', 'Post Type General Name', 'genesis' ),
        'singular_name'         => _x( 'Summit Interview', 'Post Type Singular Name', 'genesis' ),
        'menu_name'             => __( 'Summit Interviews', 'genesis' ),
        'name_admin_bar'        => __( 'Summit Interview', 'genesis' ),
        'parent_item_colon'     => __( 'Parent Summit Interview:', 'genesis' ),
        'all_items'             => __( 'All Summit Interviews', 'genesis' ),
        'add_new_item'          => __( 'Add New Summit Interview', 'genesis' ),
        'add_new'               => __( 'Add New', 'genesis' ),
        'new_item'              => __( 'New Summit Interview', 'genesis' ),
        'edit_item'             => __( 'Edit Summit Interview', 'genesis' ),
        'update_item'           => __( 'Update Summit Interview', 'genesis' ),
        'view_item'             => __( 'View Summit Interview', 'genesis' ),
        'search_items'          => __( 'Search Summit Interview', 'genesis' ),
        'not_found'             => __( 'Not found', 'genesis' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'genesis' ),
        'items_list'            => __( 'Summit Interviews list', 'genesis' ),
        'items_list_navigation' => __( 'Summit Interviews list navigation', 'genesis' ),
        'filter_items_list'     => __( 'Filter summit interview list', 'genesis' ),
    );
    $rewrite = array(
        'slug'                  => PARENT_SLUG . 'summit-interviews',
        'with_front'            => true,
        'pages'                 => true,
        'feeds'                 => true,
    );
    $args = array(
        'label'                 => __( 'Summit Interview', 'genesis' ),
        'description'           => __( 'Summit Interview', 'genesis' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'author', ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-playlist-video',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => PARENT_SLUG . 'summit-interviews',
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'rewrite'               => $rewrite,
        'capability_type'       => 'page',
    );
    register_post_type( 'summit_interview', $args );

}
add_action( 'init', 'ora_summit_interview', 0 );
}

function ora_membership_flush_rewrite_rules() {
    ora_ebook();
    ora_expert_interview();
    ora_summit_interview();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'ora_membership_flush_rewrite_rules' );
