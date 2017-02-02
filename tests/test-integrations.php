<?php
namespace AffWP\Integrations;

use \AffWP\Tests\UnitTestCase;

/**
 * Tests for Affiliate_WP_Integrations.
 *
 * @group integrations
 */
class Tests extends UnitTestCase {

	/**
	 * Integrations text fixture.
	 *
	 * @access protected
	 * @var    \Affiliate_WP_Integrations
	 * @static
	 */
	protected static $integrations;

	/**
	 * Set up fixtures once.
	 */
	public static function wpSetUpBeforeClass() {
		self::$integrations = new \Affiliate_WP_Integrations;
	}

	/**
	 * Tests tear down.
	 */
	public function tearDown() {
		affiliate_wp()->settings->set( array( 'integrations' => array() ), true );

		parent::tearDown();
	}

	/**
	 * @covers \Affiliate_WP_Integrations::get_integrations()
	 */
	public function test_get_integrations_should_return_integrations() {
		$integrations = array(
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
		);

		$this->assertEqualSets( $integrations, self::$integrations->get_integrations() );
	}

}
