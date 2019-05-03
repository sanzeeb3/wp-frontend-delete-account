<?php
/**
 * @since  1.0.0
 *
 * Class for frontend tasks.
 *
 * @class Woo_Delete_Account_frontend
 */
Class Woo_Delete_Account_frontend {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'perform_delete_action' ) );
	}

	/**
	 * Perform Delete Action.
	 *
	 * @since  1.0.0
	 *
	 * @return Void.
	 */
	public function perform_delete_action() {

		if ( isset( $_REQUEST['_wpnonce'] ) && isset( $_REQUEST['woo-delete'] ) ) {
			if( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'woo-delete-account' ) ) {
				return;
			}

			$user_id 	= ( int ) $_REQUEST['woo-delete'];
			$attribute 	= ( int ) get_option( 'wda_attribute' );

			require_once( ABSPATH.'wp-admin/includes/user.php' );

			wp_delete_user( $user_id, $attribute );

		}
	}
}

new Woo_Delete_Account_frontend();
