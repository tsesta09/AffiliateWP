<?php
namespace AffWP\Admin\Notices;

use AffWP\Tests\UnitTestCase;

/**
 * Tests for Affiliate_WP_Admin_Notices
 *
 * @covers \Affiliate_WP_Admin_Notices
 *
 * @group admin
 * @group notices
 */
class Tests extends UnitTestCase {

	/**
	 * Notices instance fixture.
	 *
	 * @access protected
	 * @var    \Affiliate_WP_Admin_Notices
	 * @static
	 */
	protected static $notices;

	/**
	 * User fixture for capabilities.
	 *
	 * @access protected
	 * @var    \WP_User
	 * @static
	 */
	protected static $user_id;

	/**
	 * Sets up fixtures once.
	 *
	 * @access public
	 * @static
	 */
	public static function wpSetUpBeforeClass() {
		require_once AFFILIATEWP_PLUGIN_DIR . 'includes/admin/class-notices.php';

		self::$notices = new \Affiliate_WP_Admin_Notices;

		self::$user_id = parent::affwp()->user->create( array(
			'role' => 'administrator'
		) );

		wp_set_current_user( self::$user_id );

		affiliate_wp()->settings->set( array(
			'integrations' => array(
				'edd' => 'Easy Digital Downloads'
			)
		) );

		// Flush the $wp_roles global.
		parent::_flush_roles();
	}

	/**
	 * Sets up before every test.
	 *
	 * @access public
	 */
	public function setUp() {
		parent::setUp();

		$roles = new \Affiliate_WP_Capabilities;
		$roles->add_caps();

		wp_set_current_user( self::$user_id );
	}

	/**
	 * Helper to retrieve the Notices instance.
	 *
	 * @access protected
	 *
	 * @return \Affiliate_WP_Admin_Notices Notices instance.
	 */
	protected function notices() {
		return self::$notices;
	}

	/**
	 * @covers \Affiliate_WP_Admin_Notices::show_notices()
	 */
//	public function test_show_notices_lacking_manage_affiliates_should_return_null() {
//		wp_set_current_user( 0 );
//
//		$this->assertNull( self::$notices->show_notices() );
//
//		wp_set_current_user( self::$user_id );
//	}

	/**
	 * @covers \Affiliate_WP_Admin_Notices::show_notices()
	 *
	 * @dataProvider data_notices_markup_with_GET
	 *
	 * @param array  $vars          $_GET variables to set.
	 * @param string $expected_html Expected HTML markup (or snippet).
	 */
	public function test_show_notices_GET_only( $vars, $expected_html ) {
		wp_set_current_user( self::$user_id );

		$this->_set_request_vars( $vars );

		$result = $this->get_notices_echo();

		$this->assertContains( $expected_html, $result );
	}

	/**
	 * @covers \Affiliate_WP_Admin_Notices::show_notices()
	 *
	 * @dataProvider data_with_affwp_notice
	 *
	 * @param string $value         Value of $_GET['affwp_notice'].
	 * @param string $expected_html Expected HTML markup (or snippet).
	 */
	public function test_show_notices_affwp_notice( $value, $expected_html ) {
		wp_set_current_user( self::$user_id );

		$this->_set_request_vars( array(
			'affwp_notice' => $value
		) );

		$result = $this->notices()->show_notices( false );

		$this->assertContains( $expected_html, $result );
	}
	/**
	 * Data provider for show_notices() tests leveraging only $_GET values.
	 *
	 * @return array {
	 *     @type array {
	 *         @type string|array $vars           Primary $_GET key.
	 *         @type string $expected_html The expected HTML when admin bar is rendered.
	 *     }
	 * }
	 */
	public function data_notices_markup_with_GET() {
		return array(
//			'settings-updated' => array(
//				array(
//					'settings-updated' => true,
//					'page'             => 'affiliate-wp-settings'
//				),
//				'<div class="updated"><p>Settings updated.</p></div>',
//			),
			'affwp_message' => array(
				array(
					'affwp_notice'  => 'foo',
					'affwp_message' => 'AffiliateWP Rocks',
				),
				'<div class="updated"><p>AffiliateWP Rocks</p></div>',
			)
		);
	}

	/**
	 * Data provider for show_notices() tests leveraging 'affwp_notice' values.
	 *
	 * @return array {
	 *     @type array {
	 *         @type string $notice_id     Value passed via $_GET['affwp_notice'].
	 *         @type string $expected_html The expected HTML when admin bar is rendered.
	 *     }
	 * }
	 */
	public function data_with_affwp_notice() {
		return array(
			'affiliate_added_failed' => array(
				'affiliate_added_failed',
				'<div class="error"><p>Affiliate wasn&#8217;t added, please try again.</p></div>',
			),
			'affiliate_updated' => array(
				'affiliate_updated',
				'<div class="updated"><p>Affiliate updated successfully',
			),
			'affiliate_update_failed' => array(
				'affiliate_update_failed',
				'<div class="error"><p>Affiliate update failed, please try again</p></div>',
			),
			'affiliate_deleted' => array(
				'affiliate_deleted',
				'<div class="updated"><p>Affiliate account(s) deleted successfully</p></div>',
			),
			'affiliate_delete_failed' => array(
				'affiliate_delete_failed',
				'<div class="error"><p>Affiliate deletion failed, please try again</p></div>',
			),
			'affiliate_activated' => array(
				'affiliate_activated',
				'<div class="updated"><p>Affiliate account activated</p></div>',
			),
			'affiliate_deactivated' => array(
				'affiliate_deactivated',
				'<div class="updated"><p>Affiliate account deactivated</p></div>',
			),
			'affiliate_accepted' => array(
				'affiliate_accepted',
				'<div class="updated"><p>Affiliate request was accepted</p></div>',
			),
			'affiliate_rejected' => array(
				'affiliate_rejected',
				'<div class="updated"><p>Affiliate request was rejected</p></div>',
			),
			'affiliates_migrated' => array(
				'affiliates_migrated',
				'added successfully',
			),
			'affiliates_pro_migrated' => array(
				'affiliates_pro_migrated',
				'added successfully',
			),
			'stats_recounted' => array(
				'stats_recounted',
				'<div class="updated"><p>Affiliate stats have been recounted!</p></div>',
			),
			'referral_added' => array(
				'referral_added',
				'<div class="updated"><p>Referral added successfully</p></div>',
			),
			'referral_updated' => array(
				'referral_updated',
				'<div class="updated"><p>Referral updated successfully</p></div>',
			),
			'referral_update_failed' => array(
				'referral_update_failed',
				'<div class="updated"><p>Referral update failed, please try again</p></div>',
			),
			'referral_deleted' => array(
				'referral_deleted',
				'<div class="updated"><p>Referral deleted successfully</p></div>',
			),
			'referral_delete_failed' => array(
				'referral_delete_failed',
				'<div class="error"><p>Referral deletion failed, please try again</p></div>',
			),
			'creative_updated' => array(
				'creative_updated',
				'<div class="updated"><p>Creative updated successfully',
			),
			'creative_added' => array(
				'creative_added',
				'<div class="updated"><p>Creative added successfully</p></div>',
			),
			'creative_deleted' => array(
				'creative_deleted',
				'<div class="updated"><p>Creative deleted successfully</p></div>',
			),
			'creative_activated' => array(
				'creative_activated',
				'<div class="updated"><p>Creative activated</p></div>',
			),
			'creative_deactivated' => array(
				'creative_deactivated',
				'<div class="updated"><p>Creative deactivated</p></div>',
			),
			'settings-imported' => array(
				'settings-imported',
				'<div class="updated"><p>Settings successfully imported</p></div>',
			),
//			'license-expired' => array(
//				'license-expired',
//				'<div class="expired"><p>Your license key expired on',
//			),
			'license-revoked' => array(
				'license-revoked',
				'<div class="error"><p>Your license key has been disabled.',
			),
			'license-missing' => array(
				'license-missing',
				'<div class="error"><p>Invalid license.',
			),
			'license-invalid' => array(
				'license-invalid',
				'<div class="error"><p>Your license key is not active for this URL.',
			),
			'license-site_inactive' => array(
				'license-site_inactive',
				'<div class="error"><p>Your license key is not active for this URL.',
			),
			'license-item_name_mismatch' => array(
				'license-item_name_mismatch',
				'<div class="error"><p>This appears to be an invalid license key.</p></div>',
			),
			'license-no_activations_left' => array(
				'license-no_activations_left',
				'<div class="error"><p>Your license key has reached its activation limit.',
			),
			'api_key_generated' => array(
				'api_key_generated',
				'<div class="updated"><p>The API keys were successfully generated.</p></div>',
			),
			'api_key_failed' => array(
				'api_key_failed',
				'<div class="error"><p>The API keys could not be generated.</p></div>',
			),
			'api_key_regenerated' => array(
				'api_key_regenerated',
				'<div class="updated"><p>The API keys were successfully regenerated.</p></div>',
			),
			'api_key_revoked' => array(
				'api_key_revoked',
				'<div class="updated"><p>The API keys were successfully revoked.</p></div>',
			),
		);
	}

	/**
	 * Sets $_GET variables for the current test.
	 *
	 * @param array $vars $_GET variables to set.
	 */
	protected function _set_request_vars( $vars = array() ) {
		foreach ( $vars as $key => $value ) {
			$_GET[ $key ] = $value;
		}
	}

	protected function get_notices_echo() {
		ob_start();

		self::$notices->show_notices();

		return ob_get_clean();
	}
}
