<?php
namespace AffWP\Utils\Batch_Process;

if ( ! class_exists( 'Recount_Affiliate_Stats' ) ) {
	$stats = affiliate_wp()->utils->batch->get( 'recount-affiliate-stats' );
	require_once( $stats['file'] );
}

/**
 * Implements an upgrade routine for recount all affiliate stats.
 *
 * @see \AffWP\Utils\Batch_Process\Recount_Affiliate_Stats
 */
class Upgrade_Recount_Stats extends Recount_Affiliate_Stats {

	/**
	 * Batch process ID.
	 *
	 * @access public
	 * @since  2.0
	 * @var    string
	 */
	public $batch_id = 'recount-affiliate-stats-upgrade';

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
				$message = sprintf( __( 'Your database has been successfully upgraded. %s', 'affiliate-wp' ),
					sprintf( '<a href="">%s</a>', __( 'Dismiss Notice', 'affiliate-wp' ) )
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
		affwp_set_upgrade_complete( 'upgrade_v20_recount_unpaid_earnings' );

		// Clean up.
		parent::finish();
	}
}
