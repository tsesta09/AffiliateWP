<?php
namespace AffWP\Labs;

abstract class Feature {

	public function __construct() {
		add_filter( 'affwp_settings_labs', array( $this, 'register_labs_setting' ) );
	}

	/**
	 * Registers the labs setting.
	 *
	 * @access public
	 * @since  2.0.4
	 * @abstract
	 *
	 * @param array $settings Labs settings.
	 * @return array Modified labs settings.
	 */
	abstract public function register_labs_setting( $settings );


}
