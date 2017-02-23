<?php
namespace AffWP\Affiliate\Functions;

use AffWP\Tests\UnitTestCase;
use AffWP\Affiliate;

/**
 * Tests for Affiliate functions in affiliate-functions.php.
 *
 * @group affiliates
 * @group functions
 * @group admin
 */
class Admin_Tests extends UnitTestCase {

	/**
	 * Users fixture.
	 *
	 * @access protected
	 * @var array
	 * @static
	 */
	protected static $users = array();

	/**
	 * Affiliates fixture.
	 *
	 * @access protected
	 * @var array
	 * @static
	 */
	protected static $affiliates = array();

	/**
	 * Set up fixtures once.
	 */
	public static function wpSetUpBeforeClass() {

		self::$users = parent::affwp()->user->create_many( 3 );

		foreach ( self::$users as $user ) {
			self::$affiliates[] = parent::affwp()->affiliate->create( array(
				'user_id' => $user
			) );
		}

	}

	/**
	 * @covers ::affwp_get_affiliate_id()
	 */
	public function test_get_affiliate_id_with_empty_user_id_and_logged_in_affiliate_user_should_return_that_affiliate_id() {
		wp_set_current_user( self::$users[1] );

		$this->assertEquals( self::$affiliates[1], affwp_get_affiliate_id() );
	}

	/**
	 * @covers ::affwp_get_affiliate_id()
	 * @preserveGlobalState disabled
	 */
	public function test_get_affiliate_id_in_admin_with_user_id_empty_should_ignore_the_current_user() {
		define( 'WP_ADMIN', true );

		wp_set_current_user( self::$users[1] );

		$this->assertFalse( affwp_get_affiliate_id() );
	}

	/**
	 * @covers ::affwp_get_affiliate_id()
	 * @preserveGlobalState disabled
	 */
	public function test_get_affiliate_id_doing_ajax_with_user_id_empty_should_ignore_the_current_user() {
		define( 'DOING_AJAX', true );

		wp_set_current_user( self::$users[1] );

		$this->assertFalse( affwp_get_affiliate_id() );
	}

}
