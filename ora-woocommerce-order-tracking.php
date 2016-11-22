<?php
/*
Plugin Name: Ora WooCommerce Order Tracking
Plugin URI: https://github.com/macbookandrew/Ora-Wellness-Plugins
Description: Inegrates “Pending” and “Failed” into Infusionsoft
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
        wp_schedule_event( time(), 'hourly', 'ora_hourly_check' );;
    }
}

/**
 * Deregister cron job on de-activation
 */
register_deactivation_hook( __FILE__, 'ora_orders_deactivation' );
function ora_orders_deactivation() {
    wp_clear_scheduled_hook( 'ora_hourly_check' );
}

/**
 * Add actions to run each hour
 */
add_action( 'ora_hourly_check', 'ora_pending_orders' );
add_action( 'ora_hourly_check', 'ora_failed_orders' );

/**
 * Mark pending orders as failed if they’re more than a day old
 */
function ora_pending_orders() {
    $now = new DateTime( date( 'Y-m-d H:i:s' ) );
    $auto_failed_note = 'Failed due to payment pending for 1+ days.';

    // get all pending orders
    $pending_orders = wc_get_orders( array(
        'status'    => 'wc-pending',
        'limit'     => -1,
    ));

    // check dates on all orders
    foreach ( $pending_orders as $order ) {
        $order_date = new DateTime( $order->order_date );
        if ( $now->diff($order_date)->d >= 1 ) {
            $order = new WC_Order( $order->id );
            $order->update_status( 'wc-failed', $auto_failed_note );
        }
    }
}

/**
 * Copy failed orders to Infusionsoft
 */
function ora_failed_orders() {

}
