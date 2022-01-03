<?php

namespace WPFrontendDeleteAccount;

/**
 * Class for adding delete account tab in WooCommerce myaccount page.
 *
 * @since 1.0.0
 *
 * @since 1.2.0, changed class name from WPFDA_WooCommerce_Myaccount_Tab to WooCommerce with Namespace.
 *
 * @class WooCommerce
 */
class WooCommerce {

	/**
	 * Initialize.
	 *
	 * @since  1.3.0 Change Constructor to init.
	 */
	public function init() {

		// Return if WooCommerce is not installed.
		if ( ! defined( 'WC_VERSION' ) ) {
			return;
		}

		add_action( 'init', array( $this, 'register_endpoint' ) );
		add_filter( 'query_vars', array( $this, 'query_vars' ) );
		add_filter( 'woocommerce_account_menu_items', array( $this, 'add_wpf_delete_account_tab' ), PHP_INT_MAX );
		add_action( 'woocommerce_account_wpf-delete-account_endpoint', array( $this, 'add_content' ) );
	}

	/**
	 * Register Delete Account Endpoint.
	 */
	public function register_endpoint() {

		$permalink = get_option( 'permalink_structure' );

		if ( empty( $permalink ) ) {

			global $wp_rewrite;

			update_option( 'rewrite_rules', false );

			$wp_rewrite->set_permalink_structure( '/%postname%/' );
			$wp_rewrite->flush_rules( true );
		}

		add_rewrite_endpoint( 'wpf-delete-account', EP_ROOT | EP_PAGES );
	}

	/**
	 * Add new query var.
	 *
	 * @param array $vars vars.
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

		if ( true === apply_filters( 'wpfda_disable_delete_account_tab', false ) ) {
			return $items;
		}

				error_log( print_r( $items, true ) );

		$items['wpf-delete-account'] = get_option( 'wpfda_title', esc_html__( 'Delete Account', 'wp-frontend-delete-account' ) );
		return $items;
	}

	/**
	 * Add content to the delete account tab.
	 *
	 * @since  1.0.1
	 *
	 * @return  void.
	 */
	public function add_content() {
		//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo wpf_delete_account_content();
	}
}
