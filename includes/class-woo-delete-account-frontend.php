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

		if( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'woo-delete-account' ) ) {
			// Verify nonce and process action.
		}
	}
}

new Woo_Delete_Account_frontend();
