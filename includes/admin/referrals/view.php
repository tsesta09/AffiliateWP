<?php

$referral_id = isset( $_GET['referral_id'] ) ? absint( $_GET['referral_id'] ) : 0;

if ( ! $referral = affwp_get_referral( $referral_id ) ) {
	wp_redirect( affwp_admin_url( 'referrals' ) );
	exit;
}

?>
<div class="wrap">
	<h2><?php printf( __( 'Referral: #%d', 'affiliate-wp' ), $referral_id ); ?></h2>

	<?php
	/**
	 * Fires at the top of the view-referral report admin screen.
	 *
	 * @since 2.1
	 */
	do_action( 'affwp_view_referral_report_top', $referral_id );
	?>

	<h3><?php _ex( 'Details', 'referral', 'affiliate-wp' ); ?></h3>

	<table id="affwp_referral_details" class="affwp_table">

		<thead>

			<tr>
				<th><?php _e( 'Affiliate ID', 'affiliate-wp' ); ?></th>
				<th><?php _e( 'Visit ID', 'affiliate-wp' ); ?></th>
				<th><?php _e( 'Description', 'affiliate-wp' ); ?></th>
				<th><?php _e( 'Status', 'affiliate-wp' ); ?></th>
				<th><?php _e( 'Amount', 'affiliate-wp' ); ?></th>
				<th><?php _e( 'Currency', 'affiliate-wp' ); ?></th>
				<th><?php _e( 'Custom Info', 'affiliate-wp' ); ?></th>
				<th><?php _e( 'Context', 'affiliate-wp' ); ?></th>
				<th><?php _e( 'Campaign', 'affiliate-wp' ); ?></th>
				<th><?php _e( 'Reference', 'affiliate-wp' ); ?></th>

				<?php
				/**
				 * Fires in the view-referral-report screens table element header.
				 *
				 * @since 2.1
				 *
				 * @param int $referral_id Referral ID.
				 */
				do_action( 'affwp_view_referral_report_table_header', $referral_id );
				?>
			</tr>

		</thead>

		<tbody>

			<tr>
				<td><?php echo esc_html( $referral->affiliate_id ); ?></td>
				<td><?php echo esc_html( $referral->visit_id ); ?></td>
				<td><?php echo esc_html( $referral->description ); ?></td>
				<td><?php echo esc_html( $referral->status ); ?></td>
				<td><?php echo affwp_currency_filter( affwp_format_amount( $referral->amount ) ); ?></td>
				<td><?php echo esc_html( $referral->currency ); ?></td>
				<td><?php echo esc_html( $referral->custom ); ?></td>
				<td><?php echo esc_html( $referral->context ); ?></td>
				<td><?php echo esc_html( $referral->campaign ); ?></td>
				<td><?php echo esc_html( $referral->reference ); ?></td>
				<?php
				/**
				 * Fires at the bottom of view-referral-report screens table element rows.
				 *
				 * @since 2.1
				 *
				 * @param int $referral_id Referral ID.
				 */
				do_action( 'affwp_view_referral_report_table_row', $referral_id );
				?>
			</tr>

		</tbody>

	</table>

	<?php
	/**
	 * Fires at the bottom of view-referral-report screens.
	 *
	 * @since 2.1
	 *
	 * @param int $referral_id Referral ID.
	 */
	do_action( 'affwp_view_referral_report_bottom', $referral_id );
	?>

</div>
