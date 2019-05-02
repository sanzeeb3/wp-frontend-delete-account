<?php
/**
 * Core Functions of the plguin for both frontend and backend.
 *
 * @since  1.0.0
 */

/**
 * Delete Account content.
 *
 * @return Void.
 */
function woo_delete_account_content() {
	$button  = get_option( 'wda_button_label', 'Confirm' );
	$user_id = get_current_user_id();
	$link    = add_query_arg(
				array(
						'woo-delete' => $user_id
					),
				esc_url( wp_nonce_url( home_url(), 'woo-delete-account' ) )
			);

	?>
		<a href="<?php echo $link;?>"><?php echo $button;?></a>
	<?php
}
