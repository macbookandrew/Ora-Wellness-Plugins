<?php
/*
Plugin Name: Ora WooCommerce Order Tracking
Plugin URI: https://github.com/PressedSolutions/Ora-Wellness-Plugins
Description: Inegrates “Pending” and “Failed” orders into Infusionsoft
Version: 1.1
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
    $contactService = new Infusionsoft_ContactServiceBase();
    $invoiceService = new Infusionsoft_InvoiceService();
    $dataService = new Infusionsoft_DataService();

    // get all pending orders
    $pending_orders = wc_get_orders( array(
        'status'    => 'wc-pending',
        'limit'     => -1,
    ));

    // check dates on all orders
    foreach ( $pending_orders as $order ) {
        $order_date = new DateTime( $order->order_date );
        if ( $now->diff($order_date)->d >= 1 ) {

            // get customer Infusionsoft info
            $infusionsoft_contact_id = $contactService->addWithDupCheck( array( 'Email' => $order->billing_email ), 'Email' );

            // set up Infusionsoft invoice
            $infusionsoft_invoice_id = $invoiceService->createBlankOrder( $infusionsoft_contact_id, 'WooCommerce Order #WC-' . $order->id, $order_date->format( 'Ymd' ) . 'T' . $order_date->format( 'H:i:s' ), 0, 0 );

            // get order items and IDs and add to Infusionsoft invoice
            $wc_order = new WC_Order( $order->id );
            $order_items = $wc_order->get_items();
            foreach ( $order_items as $item ) {
                // get variation or product ID
                if ( array_key_exists( $item['variation_id'] ) && isset( $item['variation_id'] ) ) {
                    $product = new WC_Product( $item['variation_id'] );
                } else {
                    $product = new WC_Product( $item['product_id'] );
                }

                // get item SKU
                $sku = $product->get_sku();
                $infusionsoft_product = $dataService->query( new Infusionsoft_Generated_Product, array( 'Sku' => $sku ), 1000, 0 );

                // add to invoice
                $infusionsoft_order_item = $invoiceService->addOrderItem( $infusionsoft_invoice_id, $infusionsoft_product[0]->Id, 4, $item['line_subtotal'], (int) $item['qty'], $item['name'], '' );
            }

            // add Infusionsoft info to order meta
            $infusionsoft_app_name = get_option( 'infusionsoft_sdk_app_name' );
            $update_order_meta = update_post_meta( $order->id, 'infusionsoft_order_id', $infusionsoft_invoice_id );
            $update_order_meta = update_post_meta( $order->id, 'infusionsoft_invoice_id', $infusionsoft_invoice_id );
            $update_order_meta = update_post_meta( $order->id, 'infusionsoft_view_order', 'https://' . $infusionsoft_app_name . 'infusionsoft.com/Job/manageJob.jsp?view=edit&ID=' . $infusionsoft_invoice_id );
            $update_order_meta = update_post_meta( $order->id, 'infusionsoft_contact_id', $infusionsoft_contact_id );
        }
    }
}
