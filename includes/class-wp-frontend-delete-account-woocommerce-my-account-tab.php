<?php
/**
 * @since  1.0.0
 *
 * Class for adding delete account tab in WooCommerce myaccount page.
 *
 * @class WPFDA_WooCommerce_Myaccount_Tab
 */
Class WPFDA_WooCommerce_Myaccount_Tab {

	/**
	 * Constructor.
	 */
	public function __construct() {

		// Return if WooCommerce is not installed.
		if( ! defined( 'WC_VERSION' ) ) {
			return;
		}

		add_action( 'init', array( $this, 'register_endpoint' ) );
		add_filter( 'query_vars', array( $this, 'query_vars' ) );
		add_filter( 'woocommerce_account_menu_items', array( $this, 'add_wpf_delete_account_tab' ) );
		add_action( 'woocommerce_account_wpf-delete-account_endpoint', 'wpf_delete_account_content' );
	}

	/**
	 * Register Delete Account Endpoint.
	 *
	 * @return Void.
	 */
	public function register_endpoint() {
		add_rewrite_endpoint( 'wpf-delete-account', EP_ROOT | EP_PAGES );
	}

	/**
	 * Add new query var.
	 *
	 * @param array $vars vars,
	 *
	 * @return array.
	 */
	public function query_vars( $vars ) {

    	$vars[] = 'wpf-delete-account';
    	return $vars;
	}

	/**
	 * Add Delete Account tab in my account page.
	 *
	 * @param array $items myaccount Items.
	 *
	 * @return array Items including Delete Account.
	 */
	public function add_wpf_delete_account_tab( $items ) {

		$items['wpf-delete-account']	= get_option( 'wda_title', esc_html__( 'Delete Account', 'wp-frontend-delete-account' ) );
		return $items;
	}
}

new WPFDA_WooCommerce_Myaccount_Tab();
