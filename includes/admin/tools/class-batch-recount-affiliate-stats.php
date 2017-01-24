<?php
namespace AffWP\Utils\Batch_Process;

use AffWP\Utils;
use AffWP\Utils\Batch_Process as Batch;

/**
 * Implements a batch process to recount all affiliate stats.
 *
 * @see \AffWP\Utils\Batch_Process\Base
 * @see \AffWP\Utils\Batch_Process
 * @package AffWP\Utils\Batch_Process
 */
class Recount_Affiliate_Stats extends Utils\Batch_Process implements Batch\With_PreFetch {

	/**
	 * Batch process ID.
	 *
	 * @access public
	 * @since  2.0
	 * @var    string
	 */
	public $batch_id = 'recount-affiliate-stats';

	/**
	 * Capability needed to perform the current batch process.
	 *
	 * @access public
	 * @since  2.0
	 * @var    string
	 */
	public $capability = 'manage_affiliates';

	/**
	 * Number of affiliates to process per step.
	 *
	 * @access public
	 * @since  2.0
	 * @var    int
	 */
	public $per_step = 1;

	/**
	 * Initializes the batch process.
	 *
	 * This is the point where any relevant data should be initialized for use by the processor methods.
	 *
	 * @access public
	 * @since  2.0
	 */
	public function init( $data = null ) {}

	/**
	 * Pre-fetches data to speed up processing.
	 *
	 * @access public
	 * @since  2.0
	 */
	public function pre_fetch() {

		if ( false === $this->get_total_count() ) {
			$this->compile_affiliate_totals();
		}

	}

	/**
	 * Compiles and stores amount totals for all affiliates with unpaid referrals.
	 *
	 * @access public
	 * @since  2.0
	 */
	public function compile_affiliate_totals() {
		$affiliate_totals = affiliate_wp()->utils->data->get( "{$this->batch_id}_affiliate_totals", array() );

		if ( false === $affiliate_totals ) {
			$referrals = affiliate_wp()->referrals->get_referrals( array(
				'number' => -1,
				'status' => 'unpaid',
			) );

			$data_sets = array();

			foreach ( $referrals as $referral ) {
				if ( ! $referral || ( ! empty( $status ) && $status !== $referral->status ) ) {
					continue;
				}

				$data_sets[ $referral->affiliate_id ][] = $referral;
			}

			$affiliate_totals = array();

			if ( ! empty( $data_sets ) ) {
				foreach ( $data_sets as $affiliate_id => $referrals ) {
					foreach ( $referrals as $referral ) {
						if ( isset( $affiliate_totals[ $referral->affiliate_id ] ) ) {
							$affiliate_totals[ $referral->affiliate_id ] += $referral->amount;
						} else {
							$affiliate_totals[ $referral->affiliate_id ] = $referral->amount;
						}
					}
				}
			}

			affiliate_wp()->utils->data->write( "{$this->batch_id}_affiliate_totals", $affiliate_totals );

			$this->set_total_count( count( $affiliate_totals ) );
		}

	}

	/**
	 * Processes a single step (batch).
	 *
	 * @access public
	 * @since  2.0
	 */
	public function process_step() {
		$offset        = $this->get_offset();
		$current_count = $this->get_current_count();

		$affiliate_totals = affiliate_wp()->utils->data->get( "{$this->batch_id}_affiliate_totals", array() );
		$affiliate_ids    = array_keys( $affiliate_totals );

		if ( isset( $affiliate_ids[ $offset ] ) ) {
			$affiliate_id = $affiliate_ids[ $offset ];
		} else {
			return 'done';
		}

		// Replace unpaid earnings for the current affiliate.
		affwp_increase_affiliate_unpaid_earnings( $affiliate_id, floatval( $affiliate_totals[ $affiliate_id ] ), $replace = true );

		$this->set_current_count( absint( $current_count ) + 1 );

		return ++$this->step;
	}

	/**
	 * Retrieves a message based on the given message code.
	 *
	 * @access public
	 * @since  2.0
	 *
	 * @param string $code Message code.
	 * @return string Message.
	 */
	public function get_message( $code ) {
		switch( $code ) {

			case 'done':
				$final_count = $this->get_current_count();

				$message = sprintf(
					_n(
						'%s affiliate&#8217;s was successfully processed.',
						'%s affiliates&#8217; were successfully processed.',
						$final_count,
						'affiliate-wp'
					), number_format_i18n( $final_count )
				);
				break;

			default:
				$message = '';
				break;
		}

		return $message;
	}

	/**
	 * Defines logic to execute after the batch processing is complete.
	 *
	 * @access public
	 * @since  2.0
	 */
	public function finish() {
		// Clean up.
		affiliate_wp()->utils->data->delete( "{$this->batch_id}_affiliate_totals" );

		$this->delete_counts();

		// Invalidate the affiliates cache.
		wp_cache_set( 'last_changed', microtime(), 'affiliates' );
	}

}
