<?php
namespace AffWP\Visit\Database;

use AffWP\Tests\UnitTestCase;

/**
 * Tests for Affiliate_WP_Visits_DB class
 *
 * @covers Affiliate_WP_Visits_DB
 * @group database
 * @group visits
 */
class Tests extends UnitTestCase {

	/**
	 * Test affiliates.
	 *
	 * @access public
	 * @var array
	 */
	public static $affiliates = array();

	/**
	 * Test visits.
	 *
	 * @access public
	 * @var array
	 */
	public static $visits = array();

	/**
	 * Set up fixtures once.
	 */
	public static function wpSetUpBeforeClass() {
		self::$affiliates = parent::affwp()->affiliate->create_many( 4 );

		for ( $i = 0; $i <= 3; $i++ ) {
			self::$visits[ $i ] = parent::affwp()->visit->create( array(
				'context' => "foo-{$i}"
			) );
		}

		// Create a visit with an empty context.
		self::$visits[4] = parent::affwp()->visit->create();
	}

	/**
	 * @covers \Affiliate_WP_Visits_DB::get_columns()
	 */
	public function test_get_columns_should_return_all_columns() {
		$columns = array(
			'visit_id'     => '%d',
			'affiliate_id' => '%d',
			'referral_id'  => '%d',
			'url'          => '%s',
			'referrer'     => '%s',
			'campaign'     => '%s',
			'context'      => '%s',
			'ip'           => '%s',
			'date'         => '%s',
		);

		$this->assertEqualSets( $columns, affiliate_wp()->visits->get_columns() );
	}

	/**
	 * @covers Affiliate_WP_Visits_DB::get_visits()
	 */
	public function test_get_visits_should_return_array_of_Visit_objects_if_not_count_query() {
		$results = affiliate_wp()->visits->get_visits();

		// Check a random visit.
		$this->assertInstanceOf( 'AffWP\Visit', $results[0] );
	}

	/**
	 * @covers Affiliate_WP_Visits_DB::get_visits()
	 */
	public function test_get_visits_should_turn_integer_if_count_query() {
		$results = affiliate_wp()->visits->get_visits( array(), $count = true );

		$this->assertTrue( is_numeric( $results ) );
	}

	/**
	 * @covers Affiliate_WP_Visits_DB::get_visits()
	 */
	public function test_get_visits_fields_ids_should_return_an_array_of_ids_only() {
		$results = affiliate_wp()->visits->get_visits( array(
			'fields' => 'ids'
		) );

		$this->assertEqualSets( self::$visits, $results );
	}

	/**
	 * @covers Affiliate_WP_Visits_DB::get_visits()
	 */
	public function test_get_visits_invalid_fields_arg_should_return_regular_Visit_object_results() {
		$visits = array_map( 'affwp_get_visit', self::$visits );

		$results = affiliate_wp()->visits->get_visits( array(
			'fields' => 'foo'
		) );

		$this->assertEqualSets( $visits, $results );

	}

	/**
	 * @covers Affiliate_WP_Visits_DB::get_visits()
	 */
	public function test_get_visits_with_singular_visit_id_should_return_that_visit() {
		$results = affiliate_wp()->visits->get_visits( array(
			'visit_id' => self::$visits[0],
			'fields'   => 'ids',
		) );

		$this->assertSame( self::$visits[0], $results[0] );
	}

	/**
	 * @covers Affiliate_WP_Visits_DB::get_visits()
	 */
	public function test_get_visits_with_multiple_visits_should_return_only_those_visits() {
		$visits = array( self::$visits[1], self::$visits[3] );

		$results = affiliate_wp()->visits->get_visits( array(
			'visit_id' => $visits,
			'fields'   => 'ids',
		) );

		$this->assertEqualSets( $visits, $results );
	}

	/**
	 * @covers \Affiliate_WP_Visits_DB::get_visits()
	 */
	public function test_get_visits_with_single_context_should_return_visits_with_that_context() {
		$results = affiliate_wp()->visits->get_visits( array(
			'context' => 'foo-0',
			'fields'  => 'ids',
		) );

		$this->assertEqualSets( array( self::$visits[0] ), $results );
	}

	/**
	 * @covers \Affiliate_WP_Visits_DB::get_visits()
	 */
	public function test_get_visits_with_array_of_contexts_should_return_visits_with_those_contexts() {
		$visits = array( self::$visits[1], self::$visits[3] );

		$results = affiliate_wp()->visits->get_visits( array(
			'context' => array( 'foo-1', 'foo-3' ),
			'fields'  => 'ids',
		) );

		$this->assertEqualSets( $visits, $results );
	}

	/**
	 * @covers \Affiliate_WP_Visits_DB::get_visits()
	 */
	public function test_get_visits_with_empty_context_and_not_equals_compare_should_return_visits_with_non_empty_context() {
		$results = affiliate_wp()->visits->get_visits( array(
			'fields'          => 'ids',
			'context_compare' => '!='
		) );

		$this->assertFalse( in_array( self::$visits[4], $results, true ) );
	}

	/**
	 * @covers \Affiliate_WP_Visits_DB::get_visits()
	 */
	public function test_get_visits_with_array_contexts_and_not_equals_compare_should_return_visits_not_containing_those_contexts() {
		$visits = array( self::$visits[2], self::$visits[3], self::$visits[4] );

		$results = affiliate_wp()->visits->get_visits( array(
			'context'         => array( 'foo-0', 'foo-1' ),
			'context_compare' => '!=',
			'fields'          => 'ids',
		) );

		$this->assertEqualSets( $visits, $results );
	}

	/**
	 * @covers \Affiliate_WP_Visits_DB::get_visits()
	 */
	public function test_get_visits_with_EMPTY_context_compare_should_return_visits_with_empty_context_only() {
		$results = affiliate_wp()->visits->get_visits( array(
			'context_compare' => 'EMPTY',
			'fields'          => 'ids',
		) );

		$this->assertEqualSets( array( self::$visits[4] ), $results );
	}

	/**
	 * @covers \Affiliate_WP_Visits_DB::get_visits()
	 */
	public function test_get_visits_with_NOT_EMPTY_context_compare_should_return_visits_with_not_empty_contexts() {
		$visits = array( self::$visits[0], self::$visits[1], self::$visits[2], self::$visits[3] );

		$results = affiliate_wp()->visits->get_visits( array(
			'context_compare' => 'NOT EMPTY',
			'fields'          => 'ids',
		) );

		$this->assertEqualSets( $visits, $results );
	}

	/**
	 * @covers \Affiliate_WP_Visits_DB::get_visits()
	 */
	public function test_get_visits_with_no_context_and_no_context_compare_should_return_all_visits() {
		$results = affiliate_wp()->visits->get_visits( array(
			'fields' => 'ids',
		) );

		$this->assertEqualSets( self::$visits, $results );
	}

}
