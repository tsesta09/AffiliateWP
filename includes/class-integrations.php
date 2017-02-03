<?php

class Affiliate_WP_Integrations {

	public function __construct() {

		$this->load();

	}

	/**
	 * Gets all available AffiliateWP integrations
	 *
	 * @since  1.0
	 *
	 * @return array $integrations  Available AffiliateWP integrations.
	 */
	public function get_integrations() {

		/**
		 * An array of all available AffiliateWP integrations.
		 * Append to and return the array to define a new integration.
		 *
		 * @param array  $integrations  Available AffiliateWP integrations.
		 * @since 1.0
		 */
		return apply_filters( 'affwp_integrations', array(
			'contactform7'   => 'Contact Form 7',
			'edd'            => 'Easy Digital Downloads',
			'caldera-forms'  => 'Caldera Forms',
			'formidablepro'  => 'Formidable Pro',
			'give'           => 'Give',
			'gravityforms'   => 'Gravity Forms',
			'exchange'       => 'iThemes Exchange',
			'jigoshop'       => 'Jigoshop',
			'lifterlms'      => 'LifterLMS',
			'marketpress'    => 'MarketPress',
			'membermouse'    => 'MemberMouse',
			'memberpress'    => 'MemberPress',
			'ninja-forms'    => 'Ninja Forms',
			'optimizemember' => 'OptimizeMember',
			'paypal'         => 'PayPal',
			'pmp'            => 'Paid Memberships Pro',
			'pms'            => 'Paid Member Subscriptions',
			'rcp'            => 'Restrict Content Pro',
			's2member'       => 's2Member',
			'shopp'	         => 'Shopp',
			'sproutinvoices' => 'Sprout Invoices',
			'stripe'         => 'Stripe Checkout (through WP Simple Pay)',
			'woocommerce'    => 'WooCommerce',
			'wpeasycart'     => 'WP EasyCart',
			'wpec'           => 'WP eCommerce',
			'wpforms'        => 'WPForms',
			'wp-invoice'     => 'WP-Invoice',
			'zippycourses'   => 'Zippy Courses',
		) );

	}

	/**
	 * Gets the currently-enabled AffiliateWP integrations.
	 *
	 * @access public
	 * @since  1.0
	 *
	 * @return array The currently-enabled AffiliateWP integrations.
	 */
	public function get_enabled_integrations() {
		return affiliate_wp()->settings->get( 'integrations', array() );
	}

	/**
	 * Checks if the specified integration is a valid AffiliateWP integration.
	 *
	 * @access public
	 * @since  2.1
	 *
	 * @param string $integration The integration to check.
	 * @return bool True if a valid integration, otherwise false.
	 */
	public function integration_is_valid( $integration ) {
		$all_integrations = $this->get_integrations();

		if ( array_key_exists( $integration, $all_integrations ) ) {
			return true;
		}

		/**
		 * Defines whether the specified integration is a valid AffiliateWP integration.
		 *
		 * Provide a boolean value to adjust the return of this filter.
		 *
		 * @param boolean Whether or not the integration is valid.
		 * @since 2.1
		 */
		return (bool) apply_filters( 'affwp_integration_is_valid', array_key_exists( $integration, $all_integrations ) );
	}

	/**
	 * Checks if the specified AffiliateWP integration is enabled.
	 *
	 * @access public
	 * @since  2.1
	 *
	 * @param string $integration The integration to check.
	 * @return bool True if enabled, otherwise false.
	 */
	public function integration_is_enabled( $integration ) {

		if ( ! $this->integration_is_valid( $integration ) ) {
			return false;
		}

		$enabled_integrations = $this->get_enabled_integrations();

		/**
		 * Defines whether the specified integration is enabled..
		 *
		 * Provide a boolean value to adjust the return of this filter.
		 *
		 * @param boolean Whether or not the integration is enabled.
		 * @since 2.1
		 */
		return (bool) apply_filters( 'affwp_integration_is_enabled', array_key_exists( $integration, $enabled_integrations ) );
	}

	public function load() {

		// Load each enabled integrations
		require_once AFFILIATEWP_PLUGIN_DIR . 'includes/integrations/class-base.php';

		$enabled = apply_filters( 'affwp_enabled_integrations', $this->get_enabled_integrations() );

		/**
		 * Fires immediately prior to AffiliateWP integrations being loaded.
		 */
		do_action( 'affwp_integrations_load' );

		foreach( $enabled as $filename => $integration ) {

			if( file_exists( AFFILIATEWP_PLUGIN_DIR . 'includes/integrations/class-' . $filename . '.php' ) ) {
				require_once AFFILIATEWP_PLUGIN_DIR . 'includes/integrations/class-' . $filename . '.php';
			}

		}

		/**
		 * Fires immediately after all AffiliateWP integrations are loaded.
		 */
		do_action( 'affwp_integrations_loaded' );

	}

}
