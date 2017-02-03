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

	/**
	 * @covers \Affiliate_WP_Integrations::get_enabled_integrations()
	 */
	public function test_get_enabled_integrations_should_return_enabled_integrations() {
		$integrations =  array(
			'edd'         => 'Easy Digital Downloads',
			'woocommerce' => 'WooCommerce'
		);

		affiliate_wp()->settings->set( array(
			'integrations' => $integrations
		) );

		$this->assertEqualSets( $integrations, self::$integrations->get_enabled_integrations() );
	}

	/**
	 * @covers \Affiliate_WP_Integrations::integration_is_valid()
	 */
	public function test_integration_is_valid_should_return_true_if_valid_integration() {
		$this->assertTrue( self::$integrations->integration_is_valid( 'edd' ) );
	}

	/**
	 * @covers \Affiliate_WP_Integrations::integration_is_valid()
	 */
	public function test_integration_is_valid_should_return_false_if_integration_is_invalid() {
		$this->assertFalse( self::$integrations->integration_is_valid( 'foo' ) );
	}

	/**
	 * @covers \Affiliate_WP_Integrations::integration_is_enabled()
	 */
	public function test_integration_is_enabled_should_return_false_if_integration_is_invalid() {
		$this->assertFalse( self::$integrations->integration_is_enabled( 'foo' ) );
	}

	/**
	 * @covers \Affiliate_WP_Integrations::integration_is_enabled()
	 */
	public function test_integration_is_enabled_should_return_true_if_integration_is_valid_and_enabled() {
		affiliate_wp()->settings->set( array(
			'integrations' => array( 'wpec' => 'WP eCommerce' )
		) );

		$this->assertTrue( self::$integrations->integration_is_enabled( 'wpec' ) );
	}

	/**
	 * @covers \Affiliate_WP_Integrations::integration_is_enabled()
	 */
	public function test_integration_is_enabled_should_return_false_if_integration_is_valid_but_not_enabled() {
		$this->assertTrue( self::$integrations->integration_is_valid( 'shopp' ) );

		$this->assertFalse( self::$integrations->integration_is_enabled( 'shopp' ) );
	}

}
