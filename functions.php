<?php
/**
 * Core Functions of the plugin for both frontend and backend.
 *
 * @since 1.0.0
 *
 * @since 1.2.0 Rename filename from functions-wp-frontend-delete-account.php to Functions.php
 *
 * @since 1.3.0 Move from includes/ dir to root dir.
 */

/**
 * Delete Account content.
 *
 * @return string
 */
function wpf_delete_account_content() {

	if ( ! is_user_logged_in() ) {
		return;
	}

	// TODO:: deprecate filters "wp_frontend_delete_account_before_content" and "wp_frontend_delete_account_after_content".
	ob_start();

		?>
			<div class='wpfda-delete-account-container'>
			</div>
		<?php

	return ob_get_clean();
}
