<?php
// Procedural functions for the contactform7 integration.

add_action('wp_ajax_nopriv_affwp_cf7_ajax', 'affwp_cf7_ajax', 9999 );
add_action('wp_ajax_affwp_cf7_ajax', 'affwp_cf7_ajax', 9999 );

function affwp_cf7_ajax() {
    $affwp_cf7 = new Affiliate_WP_Contact_Form_7;

    return $affwp_cf7->ajax_get_paypal_meta();
}
