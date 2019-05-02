<?php
/**
 * @since  1.0.0
 *
 * Class for backend tasks.
 *
 * @class woo_delete_account_Backend
 */
Class Woo_Delete_Account_Backend {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action('admin_menu', array( $this, 'wda_register_setting_menu') );
		add_action( 'admin_init', array( $this, 'save_settings' ) );
	}

	/**
	 * Add Woo Delete Account Submenu
	 *
	 * @since  1.0.0
	 *
	 * @return void.
	 */
	public function wda_register_setting_menu() {
  		add_options_page( 'Woo Delete Account Settings', 'Woo Delete Account', 'manage_options', 'Woo Delete Account', array( $this, 'wda_settings_page') );
	}

	/**
	 * Creates settings page.
	 *
	 * @since  1.0.0
	 *
	 * @return void.
	 */
	public function wda_settings_page(){
		$title  = get_option( 'wda_title', 'Delete Account' );
		$button = get_option( 'wda_button_label', 'Confirm' );

  		?>
  		<h2 class="wp-heading-inline"><?php esc_html_e( 'Woo Delete Account Settings', 'woo-delete-account' ); ?></h2>
        <hr class="wp-header-end">
        <hr/>

		<form method="post">
		    <table class="form-table">
		        <tr valign="top">
		        	<th scope="row"><?php echo esc_html__( 'Title', 'woo-delete-account' );?></th>
		        		<td><input type="text" name="wda_title" value ="<?php echo esc_html( $title ); ?>" class="woo-delete-account-title" />
		        		</td>
		        </tr>

		        <tr valign="top">
		        	<th scope="row"><?php echo esc_html__( 'Button Label', 'woo-delete-account' );?></th>
		        		<td><input type="text" name="wda_button_label" value ="<?php echo esc_html( $button ); ?>" class="woo-delete-account-button-label" />
		        		</td>
		        </tr>

		        <?php do_action( 'woo_delete_account_settings' );?>
	            <?php wp_nonce_field( 'woo_delete_account_settings', 'woo_delete_account_settings_nonce' );?>

		    </table>

		    <?php submit_button(); ?>

		</form>
    <?php
	}

	/**
	 * Save settings.
	 *
	 * @since  1.0.0
	 *
	 * @return void.
	 */
	public function save_settings() {

		if( isset( $_POST['woo_delete_account_settings_nonce'] ) ) {


			if( ! wp_verify_nonce( $_POST['woo_delete_account_settings_nonce'], 'woo_delete_account_settings' )
				) {
				   print 'Nonce Failed!';
				   exit;
			}

			$options = array( 'wda_title', 'wda_button_label' );

			foreach( $options as $option ) {
				if( isset( $_POST[ $option ] ) ) {
					$value = sanitize_text_field( $_POST[ $option ] );
					update_option( $option, $value);
				}
			}
		}
	}
}

new Woo_Delete_Account_Backend();
