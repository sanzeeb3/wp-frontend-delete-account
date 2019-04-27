<?php
/**
 * @since  1.0.0
 *
 * Class for backend tasks.
 * 
 * @class Delete_Account_Backend
 */
Class Delete_Account_Backend {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action('admin_menu', array( $this, 'da_register_setting_menu') );
	}

	/**
	 * Add Delete Account Submenu
	 *
	 * @since  1.0.0
	 * 
	 * @return void.
	 */
	public function da_register_setting_menu() {
  		add_options_page( 'Delete Account Settings', 'Delete Account', 'manage_options', 'Delete Account', array( $this, 'da_settings_page') );
	}

	/**
	 * Creates settings page.
	 *
	 * @since  1.0.0
	 * 
	 * @return void.
	 */
	public function da_settings_page(){
		$title  = get_option( 'da_title', 'Delete Account' );
		$button = get_option( 'da_button_label', 'Confirm' );

  		?>
  		<h2 class="wp-heading-inline"><?php esc_html_e( 'Delete Account Settings', 'delete-account' ); ?></h2>
        <hr class="wp-header-end">
        <hr/>

		<form method="post">
		    <table class="form-table">
		        <tr valign="top">
		        	<th scope="row"><?php echo esc_html__( 'Title', 'delete-account' );?></th>
		        		<td><input type="text" name="da_title" value ="<?php echo esc_html( $title ); ?>" class="delete-account-title" />
		        		</td>
		        </tr>

		        <tr valign="top">
		        	<th scope="row"><?php echo esc_html__( 'Button Label', 'delete-account' );?></th>
		        		<td><input type="text" name="da_button_label" value ="<?php echo esc_html( $button ); ?>" class="delete-account-button-label" />
		        		</td>
		        </tr>
	
		        <?php do_action( 'delete_account_settings' );?>
	            <?php wp_nonce_field( 'delete_account_settings', 'delete_account_settings_nonce' );?>

		    </table>

		    <?php submit_button(); ?>

		</form>
    <?php
	}
}

new Delete_Account_Backend();
