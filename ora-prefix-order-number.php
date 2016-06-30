<?php
/*
Plugin Name: Ora WooCommerce Order Number
Plugin URI: https://github.com/macbookandrew/Ora-Wellness-Plugins
Description: Adds “WC-” prefix to all order numbers to help with EFS integration
Version: 1.0.0
Author: Andrew Minion/Pressed Solutions
Author URI: http://www.pressedsolutions.com
Text Domain: genesis
*/

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add a prefix to the order numbers
 * @param  integer $oldnumber original WooCommerce order number
 * @param  object  $order     order information
 * @return string  new order number
 */
function ora_woocommerce_order_number( $oldnumber, $order ) {
	return 'WC-' . $order->id;
}
add_filter( 'woocommerce_order_number', 'ora_woocommerce_order_number', 1, 2 );
