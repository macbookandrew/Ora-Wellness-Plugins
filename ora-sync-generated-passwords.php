<?php
/*
Plugin Name: Ora WooCommerce Customer Password to Infusionsoft
Plugin URI: https://github.com/macbookandrew/Ora-Wellness-Plugins
Description: Adds generated passwords to Infusionsoft upon checkout using i2SDK
Version: 1.0.0
Author: Andrew Minion/Pressed Solutions
Author URI: http://www.pressedsolutions.com
Text Domain: genesis
*/

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Sync generated passwords to Infusionsoft
 * @param integer $customer_id        WooCommerce customer id number
 * @param array   $new_customer_data  username, password, email, role
 * @param string  $password_generated generated password
 */
function ora_sync_generated_password( $customer_id, $new_customer_data, $password_generated ) {
    global $iwpro,$i2sdk;

    // get WP user data
    $user_info = get_userdata( $customer_id );
    $infusionsoft_ID = get_user_meta( $customer_id, 'infusionsoft_user_id', true );

    // get email address
    if ( !empty( $user_info->user_email ) ) {
        $email = $user_info->user_email;
    }

    // check database for Infusionsoft ID first
    if ( $infusionsoft_ID && $infusionsoft_ID > 0 ) {
        $contact_id = $infusionsoft_ID;
    } else {
        $contact = $iwpro->app->dsFind( 'Contact', 5, 0, 'Email', $email,  array( 'Id' ) );
        if ( $contact['Id'] != null && $contact['Id'] != 0 && $contact != false ) {
            $contact_id = $contact['Id'];
        }
    }

    // update Infusionsoft with password
    $i2sdk->isdk->updateCon( $contact_id, array(
        'password'      =>$new_customer_data['user_pass'],
        '_OraPassword'  =>$new_customer_data['user_pass'],
    ));
}
add_action( 'woocommerce_created_user', 'ora_sync_generated_password', 15, 3 );

/**
 * Request a password change if password matches their email address
 * @param string $user_login user login name
 * @param class  $user       WP User
 */
function ora_force_password_change( $user_login, $user ) {
    $wp_hasher = new PasswordHash( 8, TRUE );

    if ( $wp_hasher->CheckPassword( $user->email, $user->user_pass ) ) {
        wp_safe_redirect( home_url( '/my-account/edit-account/' ) );
    }
}
add_action( 'wp_login', 'ora_force_password_change', 10, 2 );
