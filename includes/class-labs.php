<?php
/**
 * Implements the AffiliateWP Labs component.
 *
 * @since 2.0.4
 */
class Affiliate_WP_Labs {

	/**
	 * Sets up the labs feature bootstrap and feature loader.
	 *
	 * @access public
	 * @since  2.0.4
	 */
	public function __construct() {
		$this->includes();
		$this->setup();
	}

	/**
	 * Loads necessary labs files.
	 *
	 * @access private
	 * @since  2.0.4
	 */
	private function includes() {
		require_once AFFILIATEWP_PLUGIN_DIR . 'includes/abstracts/class-affwp-labs-feature.php';
		require_once AFFILIATEWP_PLUGIN_DIR . 'includes/labs/class-affiliate-area-styles.php';
	}

	/**
	 * Sets up labs features.
	 *
	 * @access private
	 * @since  2.0.4
	 */
	public function setup() {
		$affiliate_area_styles = new \AffWP\Labs\Affiliate_Area_Styles;
	}

}
