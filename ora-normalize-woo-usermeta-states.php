<?php
/*
Plugin Name: Ora WooCommerce Backend Tweaks
Plugin URI: https://github.com/macbookandrew/Ora-Wellness-Plugins
Description: Normalizes States User Meta
Version: 1.0
Author: Andrew Minion/Pressed Solutions
Author URI: http://www.pressedsolutions.com
Text Domain: genesis
*/

if (!defined('ABSPATH')) {
    exit;
}

// replace state names with abbreviations when manually adding an order
add_filter( 'woocommerce_found_customer_details', 'ora_customer_details', 10, 3 );
function ora_customer_details( $customer_data, $user_id, $type_to_load ) {
    $states = array(
        'AL' => 'Alabama',
        'AK' => 'Alaska',
        'AZ' => 'Arizona',
        'AR' => 'Arkansas',
        'CA' => 'California',
        'CO' => 'Colorado',
        'CT' => 'Connecticut',
        'DE' => 'Delaware',
        'DC' => 'District of Columbia',
        'FL' => 'Florida',
        'GA' => 'Georgia',
        'HI' => 'Hawaii',
        'ID' => 'Idaho',
        'IL' => 'Illinois',
        'IN' => 'Indiana',
        'IA' => 'Iowa',
        'KS' => 'Kansas',
        'KY' => 'Kentucky',
        'LA' => 'Louisiana',
        'ME' => 'Maine',
        'MD' => 'Maryland',
        'MA' => 'Massachusetts',
        'MI' => 'Michigan',
        'MN' => 'Minnesota',
        'MS' => 'Mississippi',
        'MO' => 'Missouri',
        'MT' => 'Montana',
        'NE' => 'Nebraska',
        'NV' => 'Nevada',
        'NH' => 'New Hampshire',
        'NJ' => 'New Jersey',
        'NM' => 'New Mexico',
        'NY' => 'New York',
        'NC' => 'North Carolina',
        'ND' => 'North Dakota',
        'OH' => 'Ohio',
        'OK' => 'Oklahoma',
        'OR' => 'Oregon',
        'PA' => 'Pennsylvania',
        'RI' => 'Rhode Island',
        'SC' => 'South Carolina',
        'SD' => 'South Dakota',
        'TN' => 'Tennessee',
        'TX' => 'Texas',
        'UT' => 'Utah',
        'VT' => 'Vermont',
        'VA' => 'Virginia',
        'WA' => 'Washington',
        'WV' => 'West Virginia',
        'WI' => 'Wisconsin',
        'WY' => 'Wyoming',
    );

    // loop through all customer data
    foreach ( $customer_data as $key => $value ) {
        // check only states
        if ( strpos( $key, '_state' ) !== false ) {
            // search states array for a match
            $array_search = array_search( $value, $states );

            // if there’s a match, use it; otherwise, use the specified data
            $customer_data[$key] = $array_search ? $array_search : $value;
        }
    }

    return $customer_data;
}

// always show country
add_filter( 'woocommerce_formatted_address_force_country_display', '__return_true' );
