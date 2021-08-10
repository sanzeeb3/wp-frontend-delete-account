<?php

namespace WPFrontendDeleteAccount;

/**
 * Class for EDD (Easy Digital Downloads).
 *
 * @since @@{version}
 */
class EDD {

	/**
	 * Initialize.
	 *
	 * @since @@{version}
	 */
	public function init() {
		add_action( 'edd_profile_editor_after_password_fields', [ $this, 'add_delete_account_section' ] );
	}

	/**
	 * Add 'Delete Account' section on EDD edit profile page.
	 *
	 * @since @@{version}
	 */
	public function add_delete_account_section() {
		?>

		<fieldset id="edd_profile_delete_account_fieldset">
			<legend id="edd_profile_delete_account_label"><?php echo get_option( 'wpfda_title', esc_html__( 'Delete Account', 'wp-frontend-delete-account' ) ); ?></legend>

				<?php echo wpf_delete_account_content(); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

		</fieldset>
		<?php
	}
}
