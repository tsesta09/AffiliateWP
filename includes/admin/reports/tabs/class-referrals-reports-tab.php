<?php
namespace AffWP\Referral\Admin\Reports;

use AffWP\Admin\Reports;

/**
 * Implements a core 'Referrals' tab for the Reports screen.
 *
 * @since 1.9
 *
 * @see \AffWP\Admin\Reports\Tab
 */
class Tab extends Reports\Tab {

	/**
	 * Affiliate to filter for (if set).
	 *
	 * @access public
	 * @since  2.1
	 * @var    int
	 */
	public $affiliate_id = 0;

	/**
	 * Sets up the Referrals tab for Reports.
	 *
	 * @access public
	 * @since  1.9
	 */
	public function __construct() {
		$this->tab_id   = 'referrals';
		$this->label    = __( 'Referrals', 'affiliate-wp' );
		$this->priority = 0;
		$this->graph    = new \Affiliate_WP_Referrals_Graph;

		$this->set_up_additional_filters();

		parent::__construct();
	}

	/**
	 * Sets up additional graph filters for the Affiliates tab in Reports.
	 *
	 * @access public
	 * @since  2.1
	 */
	public function set_up_additional_filters() {

		// Retrieve the affiliate ID if the filter is set.
		if ( ! empty( $_GET['affiliate_login'] ) ) {
			$username = sanitize_text_field( $_GET['affiliate_login'] );

			if ( $affiliate = affwp_get_affiliate( $username ) ) {
				$this->affiliate_id = $affiliate->ID;
			}
		}

		// Allow extra filters to be added by letting the Tab class render the form wrapper itself.
		$this->graph->set( 'form_wrapper', false );

		// Register the single affiliate filter.
		add_action( 'affwp_reports_referrals_nav', array( $this, 'affiliate_filter' ), 10 );
	}

	/**
	 * Adds a single affiliate filter field to the Affiliates tab in Reports.
	 *
	 * @since 2.1
	 */
	public function affiliate_filter() {
		$affiliate_login = ! empty( $_GET['affiliate_login'] ) ? sanitize_text_field( $_GET['affiliate_login'] ) : '';
		?>
		<span class="affwp-ajax-search-wrap">
			<input type="text" name="affiliate_login" id="user_name" class="affwp-user-search" value="<?php echo esc_attr( $affiliate_login ); ?>" data-affwp-status="any" autocomplete="off" placeholder="<?php _e( 'Affiliate name', 'affiliate-wp' ); ?>" />
		</span>
		<?php
	}

	/**
	 * Registers the Referrals tab tiles.
	 *
	 * @access public
	 * @since  1.9
	 */
	public function register_tiles() {
		$this->register_tile( 'all_time_paid_earnings', array(
			'label'           => __( 'Paid Earnings', 'affiliate-wp' ),
			'type'            => 'amount',
			'data'            => array_sum( affiliate_wp()->referrals->get_referrals( array(
				'number' => -1,
				'fields' => 'amount',
				'status' => 'paid'
			) ) ),
			'comparison_data' => __( 'All Time', 'affiliate-wp' )
		) );

		$this->register_tile( 'paid_earnings', array(
			'label'           => __( 'Paid Earnings', 'affiliate-wp' ),
			'context'         => 'secondary',
			'type'            => 'amount',
			'data'            => affiliate_wp()->referrals->paid_earnings( $this->date_query, 0, false ),
			'comparison_data' => $this->get_date_comparison_label(),
		) );

		$this->register_tile( 'unpaid_earnings', array(
			'label'           => __( 'Unpaid Earnings', 'affiliate-wp' ),
			'context'         => 'tertiary',
			'type'            => 'amount',
			'data'            => affiliate_wp()->referrals->unpaid_earnings( $this->date_query, 0, false ),
			'comparison_data' => $this->get_date_comparison_label(),
		) );

		$this->register_tile( 'unpaid_referrals', array(
			'label'   => __( 'Unpaid Referrals', 'affiliate-wp' ),
			'type'    => 'number',
			'data'    => affiliate_wp()->referrals->unpaid_count( $this->date_query ),
			'comparison_data' => $this->get_date_comparison_label(),
		) );

		$all_referrals = affiliate_wp()->referrals->get_referrals( array(
			'number' => -1,
			'fields' => 'amount',
		) );

		if ( ! $all_referrals ) {
			$all_referrals = array( 0 );
		}

		$this->register_tile( 'average_referral', array(
			'label'           => __( 'Average Referral Amount', 'affiliate-wp' ),
			'type'            => 'amount',
			'context'         => 'secondary',
			'data'            => array_sum( $all_referrals ) / count( $all_referrals ),
			'comparison_data' => $this->get_date_comparison_label(),
		) );
	}

	/**
	 * Handles displaying the 'Trends' graph.
	 *
	 * @access public
	 * @since  1.9
	 */
	public function display_trends() {
		$this->graph->set( 'show_controls', false );
		$this->graph->set( 'x_mode', 'time' );

		if ( $this->affiliate_id ) {
			$this->graph->set( 'affiliate_id', $this->affiliate_id );
		}

		$this->graph->display();
	}

}
