<?php
/*
Plugin Name: Ora Testimonials
Plugin URI: https://github.com/PressedSolutions/Ora-Wellness-Plugins
Description: Adds custom post type for testimonials
Version: 1.8
Author: Andrew Minion/Pressed Solutions
Author URI: http://www.pressedsolutions.com
Text Domain: genesis
*/

if (!defined('ABSPATH')) {
    exit;
}

if ( ! function_exists('ora_testimonial') ) {

// Register Custom Post Type
function ora_testimonial() {

    $labels = array(
        'name'                  => _x( 'Testimonials', 'Post Type General Name', 'genesis' ),
        'singular_name'         => _x( 'Testimonial', 'Post Type Singular Name', 'genesis' ),
        'menu_name'             => __( 'Testimonials', 'genesis' ),
        'name_admin_bar'        => __( 'Testimonial', 'genesis' ),
        'parent_item_colon'     => __( 'Parent Testimonial:', 'genesis' ),
        'all_items'             => __( 'All Testimonials', 'genesis' ),
        'add_new_item'          => __( 'Add New Testimonial', 'genesis' ),
        'add_new'               => __( 'Add New', 'genesis' ),
        'new_item'              => __( 'New Item', 'genesis' ),
        'edit_item'             => __( 'Edit Item', 'genesis' ),
        'update_item'           => __( 'Update Testimonial', 'genesis' ),
        'view_item'             => __( 'View Testimonial', 'genesis' ),
        'search_items'          => __( 'Search Testimonial', 'genesis' ),
        'not_found'             => __( 'Not found', 'genesis' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'genesis' ),
        'items_list'            => __( 'Testimonials list', 'genesis' ),
        'items_list_navigation' => __( 'Testimonials list navigation', 'genesis' ),
        'filter_items_list'     => __( 'Filter testimonials list', 'genesis' ),
    );
    $rewrite = array(
        'slug'                  => 'testimonials/all',
        'with_front'            => true,
        'pages'                 => true,
        'feeds'                 => true,
    );
    $args = array(
        'label'                 => __( 'Testimonial', 'genesis' ),
        'description'           => __( 'Testimonial', 'genesis' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'page-attributes', ),
        'hierarchical'          => true,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-testimonial',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => 'testimonials/all',
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'rewrite'               => $rewrite,
        'capability_type'       => 'page',
    );
    register_post_type( 'testimonial', $args );

}
add_action( 'init', 'ora_testimonial', 0 );

// Register Custom Taxonomy
function ora_testimonial_category() {

    $labels = array(
        'name'                       => 'Categories',
        'singular_name'              => 'Category',
        'menu_name'                  => 'Categories',
        'all_items'                  => 'All Items',
        'parent_item'                => 'Parent Item',
        'parent_item_colon'          => 'Parent Item:',
        'new_item_name'              => 'New Item Name',
        'add_new_item'               => 'Add New Item',
        'edit_item'                  => 'Edit Item',
        'update_item'                => 'Update Item',
        'view_item'                  => 'View Item',
        'separate_items_with_commas' => 'Separate items with commas',
        'add_or_remove_items'        => 'Add or remove items',
        'choose_from_most_used'      => 'Choose from the most used',
        'popular_items'              => 'Popular Items',
        'search_items'               => 'Search Items',
        'not_found'                  => 'Not Found',
        'no_terms'                   => 'No items',
        'items_list'                 => 'Items list',
        'items_list_navigation'      => 'Items list navigation',
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
    );
    register_taxonomy( 'testimonial-category', array( 'testimonial' ), $args );

}
add_action( 'init', 'ora_testimonial_category', 0 );

// Add Term Metadata
function ora_add_testimonial_category_group_field( $taxonomy ) {
    ?>
    <div class="form-field term-group">
        <label for="related-product">Related Page/Product to Feature</label>
        <select class="postform" id="related-product" name="related-product">
            <option value="0">- Select One -</option>
            <optgroup label="Pages">
                <?php
                foreach ( get_pages() as $this_page ) {
                    echo '<option value="' . $this_page->ID . '">' . $this_page->post_title . '</option>';
                }
                ?>
            </optgroup>
            <optgroup label="Products">
                <?php
                foreach ( get_posts( array( 'post_type' => 'product', 'posts_per_page' => -1 ) ) as $this_product ) {
                    echo '<option value="' . $this_product->ID . '">' . $this_product->post_title . '</option>';
                }
                ?>
            </optgroup>
        </select>
    </div>
    <?php
    add_action( 'admin_enqueue_scripts', 'ora_testimonial_category_js', 98 );
}
add_action( 'testimonial_add_form_fields', 'ora_add_testimonial_category_group_field' );

// save related page/product
function ora_save_testimonial_category_group_field( $term_id, $tt_id ) {
    if ( isset( $_POST['related-product'] ) && '' !== $_POST['related-product'] ) {
        add_term_meta( $term_id, 'related-product', esc_attr( $_POST['related-product'] ) );
    }
}
add_action( 'created_testimonial', 'ora_save_testimonial_category_group_field', 10, 2 );
add_action( 'edited_testimonial', 'ora_save_testimonial_category_group_field', 10, 2 );

// update related page/product
function ora_edit_testimonial_category_group_field( $term, $taxonomy ) {
    $related_product = get_term_meta( $term->term_id, 'related-product', true );
    ?>
    <tr class="form-field term-group-wrap">
        <th scope="row">
            <label for="related-product">Related Page/Product to Feature</label>
        </th>
        <td>
            <select class="postform" id="related-product" name="related-product">
                <option value="0">- Select One -</option>
                <optgroup label="Pages">
                    <?php
                    foreach ( get_pages() as $this_page ) {
                        echo '<option value="' . $this_page->ID . '"' . selected( $related_product, $this_page->ID ) . '>' . $this_page->post_title . '</option>';
                    }
                    ?>
                </optgroup>
                <optgroup label="Products">
                    <?php
                    foreach ( get_posts( array( 'post_type' => 'product', 'posts_per_page' => -1 ) ) as $this_product ) {
                        echo '<option value="' . $this_product->ID . '"' . selected( $related_product, $this_product->ID ) . '>' . $this_product->post_title . '</option>';
                    }
                    ?>
                </optgroup>
            </select>
        </td>
    </tr>
    <?php
}
add_action( 'testimonial_edit_form_fields', 'ora_edit_testimonial_category_group_field', 10, 2 );

// add select2 JS and style
function ora_testimonial_category_js( $hook ) {
    if ( 'term.php' == $hook || 'edit-tags.php' == $hook ) {
        wp_enqueue_script( 'testimonial-metadata', plugins_url( '/js/testimonial-metadata.js', __FILE__ ), array( 'jquery', 'select2' ) );
        ?>
        <style>
            #s2id_related-product { width: 95%; }
        </style>
        <?php
    }
}
add_action( 'admin_enqueue_scripts', 'ora_testimonial_category_js', 99 );

function ora_flush_rewrite_rules() {
    ora_testimonial();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'ora_flush_rewrite_rules' );

}

/**
 * Sidebar Widget showing one random testimonial
 */
class OraTestimonialWidget extends WP_Widget{

    function __construct() {
        // Instantiate the parent object
        parent::__construct( false, 'Ora Testimonial', array(
            'description'   => 'Outputs one or more random testimonials, optionally filtered by category',
            'classname'     => 'testimonial',
        ));
    }

    public function widget( $args, $instance ) {
        // set defaults
        $posts_per_page = isset( $instance['posts_per_page'] ) ? esc_attr( $instance['posts_per_page'] ) : '1';
        $taxonomy_id = isset( $instance['category'] ) ? esc_attr( $instance['category'] ) : NULL;

        // WP_Query arguments
        $random_testimonial_args = array (
            'post_type'              => array( 'testimonial' ),
            'posts_per_page'         => $posts_per_page,
            'orderby'                => 'rand',
        );
        if ( $taxonomy_id ) {
            $random_testimonial_args['tax_query'] = array (
                array (
                    'taxonomy'  => 'testimonial',
                    'field'     => 'term_id',
                    'terms'     => $taxonomy_id,
                ),
            );
        }

        // The Query
        $random_testimonial_query = new WP_Query( $random_testimonial_args );

        // The Loop
        if ( $random_testimonial_query->have_posts() ) {
            echo $args['before_widget'];
            while ( $random_testimonial_query->have_posts() ) {
                $random_testimonial_query->the_post();

                echo '<article class="testimonial single">';
                // content
                echo '<div class="testimonial-content-wrapper"><div class="testimonial-content">' . apply_filters( 'the_content', get_the_content() ) . '</div>
                <p class="testimonial-title alternate clearfix">' . get_the_title();
                if ( get_field( 'city' ) || get_field( 'state' ) || get_field( 'country' ) ) {
                    echo ' from ';
                    if ( get_field( 'city' ) ) echo get_field( 'city' ) . ', ';
                    if ( get_field( 'state' ) ) echo get_field( 'state' );
                    if ( 'United States' !== get_field( 'country' ) ) echo ', ' . get_field( 'country' );
                }
                echo '</div>';

                // CTA
                if ( $taxonomy_id ) {
                    $related_product = get_term_meta( $taxonomy_id, 'related-product', true );
                    if ( $related_product ) {
                        echo '<p><a class="button primary center" href="' . get_permalink( $related_product ) . '">Learn More</a></p>';
                    }
                }

                echo '</article>';
            }
            echo $args['after_widget'];
        }

        // Restore original Post Data
        wp_reset_postdata();
    }

    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        $instance['posts_per_page'] = ( ! empty( $new_instance['posts_per_page'] ) ) ? strip_tags( $new_instance['posts_per_page'] ) : '';
        $instance['category'] = ( ! empty( $new_instance['category'] ) ) ? strip_tags( $new_instance['category'] ) : '';

        return $instance;
    }

    public function form( $instance ) {
        // posts per page
        echo '<p><label for="' . $this->get_field_name( 'posts_per_page' ) . '">Number of Posts to Show: <input name="' . $this->get_field_name( 'posts_per_page' ) . '" id="' . $this->get_field_id( 'posts_per_page' ) . '" class="widefat" type="number" min="-1" step="1" value="' . esc_attr( $instance['posts_per_page'] ) . '" /></label></p>';

        // categories
        $categories = get_categories( array( 'taxonomy' => 'testimonial' ) );
        echo '<p><label for="' . $this->get_field_name( 'category' ) . '">Category:
        <select class="widefat" name="' . $this->get_field_name( 'category' ) . '" id="' . $this->get_field_id( 'category' ) . '">
            <option value="">Random Testimonial from Any Category</option>';
        foreach ( $categories as $this_category ) {
            echo '<option value="' . $this_category->term_id . '"';
            if ( ! empty( $instance['category'] ) && $this_category->term_id == $instance['category'] ) {
                echo ' selected="selected"';
            }
            echo '>' . $this_category->name . '</option>';
        }
        echo '</select>
        </label></p>';
    }
}

function ora_register_testimonial_widget() {
    register_widget( 'OraTestimonialWidget' );
}
add_action( 'widgets_init', 'ora_register_testimonial_widget' );

/**
 * Show post IDs on testimonial admin screen for use in shortcode
 * Show menu order for ease of sorting
 */
add_filter('manage_testimonial_posts_columns' , 'ora_testimonial_columns_head');
function ora_testimonial_columns_head( $columns ) {
    $columns['ID'] = 'Post ID';
    $columns['menu_order'] = 'Sort Order';

    return $columns;
}

add_action( 'manage_testimonial_posts_custom_column', 'ora_testimonial_columns_body', 10, 2 );
function ora_testimonial_columns_body( $column_name, $post_id ) {
    if ( 'ID' == $column_name ) {
        echo $post_id;
    } elseif ( 'menu_order' == $column_name ) {
        echo get_post( $post_id )->menu_order;
    }
}

/**
 * Testimonial grid shortcode
 */
function ora_testimonial_grid_shortcode( $atts, $content = NULL ) {
    return '<div class="testimonial-grid">' . apply_filters( 'the_content',  $content ) . '</div>';
}
add_shortcode( 'testimonial_grid', 'ora_testimonial_grid_shortcode' );

/**
 * Testimonial shortcode
 */
// Add Shortcode
function ora_testimonial_shortcode( $atts ) {

    // Attributes
    extract( shortcode_atts(
        array(
            'id'             => '',
            'content_only'   => false,
            'blockquote'     => false,
        ), $atts )
    );

    // WP_Query arguments
    $args = array (
        'post_type'              => array( 'testimonial' ),
        'pagination'             => false,
        'posts_per_page'         => '1',
    );
    if ( $id ) {
        $args['p'] = $id;
    }

    // The Query
    $testimonial_query = new WP_Query( $args );

    // The Loop
    if ( $testimonial_query->have_posts() ) {
        $shortcode_content = NULL;

        while ( $testimonial_query->have_posts() ) {
            $testimonial_query->the_post();

            if ( $blockquote ) {
                $shortcode_content .= '<blockquote class="testimonial single">';
            } elseif ( ! $content_only ) {
                $shortcode_content .= '<aside class="testimonial single shortcode">';
            }

            // content
            $shortcode_content .= '<div class="testimonial-content-wrapper"><div class="testimonial-content">' . apply_filters( 'the_content', get_the_content() ) . '</div>';

            if ( $blockquote ) {
                $shortcode_content .= '</blockquote>';
            } elseif ( ! $content_only ) {
                $shortcode_content .= '</aside>';
            }
        }
    }

    // Restore original Post Data
    wp_reset_postdata();

    return $shortcode_content;
}
add_shortcode( 'testimonial', 'ora_testimonial_shortcode' );

/**
 * Testimonial Categories shortcode
 */
function ora_testimonial_category_shortcode( $atts ) {
    $category_list_options = array(
        'taxonomy'      => 'testimonial-category',
        'echo'          => false,
        'title_li'      => '',
        'show_count'    => true,
    );

    return '<ul>' . wp_list_categories( $category_list_options ) . '
    </ul>';
}
add_shortcode( 'testimonial_category_list', 'ora_testimonial_category_shortcode' );

/**
 * Add testimonial custom image size
 */
function ora_testimonial_image_size() {
    add_image_size( 'testimonial-thumb', 80, 80, true );
    add_image_size( 'testimonial-medium', 160, 160, true );
    add_image_size( 'testimonial-large', 400, 400, true );
}
add_action( 'after_setup_theme', 'ora_testimonial_image_size' );

/**
 * Add testimonial author and location info on archives, tax archives, and single views
 * @return string HTML code with thumbnail, name, and location
 */
function ora_testimonial_category_meta( $content ) {
    global $post;
    if ( 'testimonial' == $post->post_type ) {
        $meta = '<p class="testimonial-title alternate clearfix' . ( has_post_thumbnail() ? ' has-thumb' : '' ) . '">';
        if ( has_post_thumbnail() ) {
            $meta .= apply_filters( 'ora_testimonial_image', get_the_post_thumbnail( get_the_ID(), 'testimonial-thumb', array( 'class' => 'testimonial-thumb alignleft' ) ) );
        }
        $meta .= get_the_title();
        if ( get_field( 'city' ) || get_field( 'state' ) || get_field( 'country' ) ) {
            $meta .= ' from ';
            if ( get_field( 'city' ) ) $meta .= get_field( 'city' ) . ', ';
            if ( get_field( 'state' ) ) $meta .= get_field( 'state' );
            if ( 'United States' !== get_field( 'country' ) ) {
                if ( get_field( 'city' ) || get_field( 'state' ) ) {
                    $meta .= ', ';
                }
                $meta .= get_field( 'country' );
            }
        }
        $meta .= '</p>';

        return $content . $meta;
    } else {
        return $content;
    }
}
add_filter( 'the_content', 'ora_testimonial_category_meta' );

/**
 * Don’t output post title for testimonials
 * @param  string $title Post title
 * @return string Post title to display
 */
function ora_testimonial_category_title( $title ) {
    global $post;
    if ( 'testimonial' == $post->post_type ) {
        return NULL;
    } else {
        return $title;
    }
}
add_filter( 'genesis_post_title_text', 'ora_testimonial_category_title' );

/**
 * Show full post content for testimonial archives and category archives
 */
function ora_testimonial_category_full_content() {
    if ( 'testimonial' == get_post_type() ) {
        if ( is_tax() || is_archive() ) {
            remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
            remove_action( 'genesis_entry_content', 'genesis_do_post_image' );
            add_action( 'genesis_entry_content', 'the_content' );
        }
    }
}
add_action( 'genesis_before', 'ora_testimonial_category_full_content' );

/**
 * Don’t show post thumbnail on testimonial archives
 */
function ora_remove_testimonial_thumbnail() {
    if ( 'testimonial' == get_post_type() ) {
        remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
    }
}
add_action( 'genesis_entry_content', 'ora_remove_testimonial_thumbnail', 5 );
