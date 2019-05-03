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
		$title  			= get_option( 'wda_title', 'Delete Account' );
		$button 			= get_option( 'wda_button_label', 'Confirm' );
		$attribute 			= get_option( 'wda_attribute' );
		$security 			= get_option( 'wda_security', 'password' );
		$password_text  	= get_option( 'wda_security_password_text', 'Enter password to confirm:' );
		$captcha_question 	= get_option( 'wda_security_custom_captcha_question', 'What is 11*3?' );
		$captcha_answer 	= get_option( 'wda_security_custom_captcha_answer', '33' );
		$site_key			= get_option( 'wda_security_recaptcha_site_key' );
		$site_secret		= get_option( 'wda_security_recaptcha_site_secret' );
		$users  			= get_users();

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

		        <tr valign="top">
		        	<th scope="row"><?php echo esc_html__( 'Attribute all contents to:', 'woo-delete-account' );?></th>
		        		<td>
		        			<select style="width:17%;" name="wda_attribute">
		        				<option><?php echo esc_html__( '--None--', 'wp-force-logout' );?></option>
		        				<?php foreach( $users as $user ) {
		        						echo '<option value="'. $user->ID .'" '. selected( $user->ID, $attribute, true ) .'>'. $user->data->user_login .'</option>';
		        					}
								?>
		        			</select>
		        		</td>
		        </tr>

		        <tr valign="top">
		        	<th scope="row"><?php echo esc_html__( 'Security method before deleting:', 'woo-delete-account' );?></th>
		        		<td>
		        			<select style="width:17%;" name="wda_security">
		        				<option value="none" <?php selected( 'none', $security, true ) ;?>><?php echo esc_html__( '--None--', 'wp-force-logout' );?></option>
   								<option value="password" <?php selected( 'password', $security, true ) ;?>><?php echo esc_html__( 'Password', 'wp-force-logout' );?></option>
		        				<option value="custom_captcha" <?php selected( 'custom_captcha', $security, true ) ;?>><?php echo esc_html__( 'Custom Captcha', 'wp-force-logout' );?></option>
		        				<option value="recaptcha_v2" <?php selected( 'recaptcha_v2', $security, true ) ;?>><?php echo esc_html__( 'reCaptcha (v2)', 'wp-force-logout' );?></option>
		        			</select><br/>
		        		</td>
		        </tr>

		        <tr valign="top" class="woo-delete-account-security-password">
		        	<th scope="row"><?php echo esc_html__( 'Confirmation Text', 'woo-delete-account' );?></th>
		        		<td>
		        			<input style="width:50%" type="text" name="wda_security_password_text" value ="<?php echo esc_html( $password_text ); ?>" class="woo-delete-account-security-password-inline" />
		        		</td>
		        </tr>

   		        <tr valign="top" class="woo-delete-account-security-custom-captcha-question">
		        	<th scope="row"><?php echo esc_html__( 'Captcha Question', 'woo-delete-account' );?></th>
		        		<td>
		        			<input style="width:50%" type="text" name="wda_security_custom_captcha_question" value ="<?php echo esc_html( $captcha_question ); ?>" class="woo-delete-account-security-custom-captcha-question-inline" />
		        		</td>
		        </tr>

		        <tr valign="top" class="woo-delete-account-security-custom-captcha-answer">
		        	<th scope="row"><?php echo esc_html__( 'Captcha Answer', 'woo-delete-account' );?></th>
		        		<td>
		        			<input style="width:50%" type="text" name="wda_security_custom_captcha_answer" value ="<?php echo esc_html( $captcha_answer ); ?>" class="woo-delete-account-security-custom-captcha-answer-inline" />
		        		</td>
		        </tr>

		        <tr valign="top" class="woo-delete-account-security-recaptcha-site-key">
		        	<th scope="row"><?php echo esc_html__( 'Site Key', 'woo-delete-account' );?></th>
		        		<td>
		        			<input style="width:50%" type="text" name="wda_security_recaptcha_site_key" value ="<?php echo esc_html( $site_key ); ?>" class="woo-delete-account-security-recaptcha-site-key-inline" />
		        		</td>
		        </tr>

		        <tr valign="top" class="woo-delete-account-security-recaptcha-site-secret">
		        	<th scope="row"><?php echo esc_html__( 'Site Secret', 'woo-delete-account' );?></th>
		        		<td>
		        			<input style="width:50%" type="text" name="wda_security_recaptcha_site_secret" value ="<?php echo esc_html( $site_secret ); ?>" class="woo-delete-account-security-recaptcha-site-secret-inline" />
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

			$options = array( 'wda_title', 'wda_button_label', 'wda_attribute', 'wda_security', 'wda_security_password_text', 'wda_security_custom_captcha_question', 'wda_security_custom_captcha_answer', 'wda_security_recaptcha_site_key', 'wda_security_recaptcha_site_secret' );

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
