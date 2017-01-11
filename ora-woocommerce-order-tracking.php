<?php
/*
Plugin Name: Ora WooCommerce Order Tracking
Plugin URI: https://github.com/PressedSolutions/Ora-Wellness-Plugins
Description: Inegrates “Pending” and “Failed” orders into Infusionsoft
Version: 1.0
Author: Andrew Minion/Pressed Solutions
Author URI: http://www.pressedsolutions.com
Text Domain: genesis
*/

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register cron job on activation
 */
register_activation_hook( __FILE__, 'ora_orders_activation' );
function ora_orders_activation() {
    if ( ! wp_next_scheduled( 'ora_pending_orders' ) ) {
        wp_schedule_event( time(), 'daily', 'ora_daily_check' );;
    }
}

/**
 * Deregister cron job on de-activation
 */
register_deactivation_hook( __FILE__, 'ora_orders_deactivation' );
function ora_orders_deactivation() {
    wp_clear_scheduled_hook( 'ora_daily_check' );
}

/**
 * Add actions to run each hour
 */
add_action( 'ora_daily_check', 'ora_pending_orders' );

/**
 * Mark pending orders as failed if they’re more than a day old
 */
function ora_pending_orders() {
    $now = new DateTime( date( 'Y-m-d H:i:s' ) );
    $auto_failed_note = 'Failed due to payment pending for 1+ days.';
    $new_status = 'wc-failed';

    // get all pending orders
    $pending_orders = wc_get_orders( array(
        'status'    => 'wc-pending',
        'limit'     => -1,
    ));

    // check dates on all orders
    foreach ( $pending_orders as $order ) {
        $order_date = new DateTime( $order->order_date );
        if ( $now->diff($order_date)->d >= 1 ) {
            // get order and update order status
            $order = new WC_Order( $order->id );
            $old_status = $order->post_status;
            // set manual flag to true to prevent date from being updated
            $order->update_status( $new_status, $auto_failed_note, true );

            // run WooCommerce hooks
            do_action( 'woocommerce_order_status_' . $old_status . '_to_' . $new_status, $order->id );
            do_action( 'woocommerce_order_status_changed', $order->id, $old_status, $new_status );

            // add to notification list
            $orders_failed[ $order->id ] = 'https://www.orawellness.com/wp-admin/post.php?action=edit&post=' . $order->id;
        }
    }
}
