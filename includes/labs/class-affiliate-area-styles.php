<?php
namespace AffWP\Labs;

use AffWP\Labs;

/**
 * Implements the Affiliate Area Styles feature for labs.
 *
 * @since 2.0.4
 *
 * @see \AffWP\Labs\Feature
 */
final class Affiliate_Area_Styles extends Labs\Feature {

	/**
	 * Registers the labs setting.
	 *
	 * @access public
	 * @since  2.0.4
	 *
	 * @param array $settings Labs settings.
	 * @return array Modified labs settings.
	 */
	public function register_labs_setting( $settings ) {
		$settings['affiliate_area_styles'] = array(
			'name' => __( 'Affiliate Area Style Customizer', 'affiliate-wp' ),
			'desc' => __( 'Adds the ability to customize styling of the affiliate area.', 'affiliate-wp' ),
			'type' => 'checkbox'
		);

		return $settings;
	}


}
