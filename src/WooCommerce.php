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
	private $endpoint;

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

		$this->endpoint = apply_filters( 'wpfda_woocommerce_endpoint', 'wpf-delete-account' );
		add_action( 'init', array( $this, 'register_endpoint' ) );
		add_action( 'wp_loaded', array( $this, 'maybe_refresh_rewrites' ) );
		add_filter( 'query_vars', array( $this, 'query_vars' ) );
		add_filter( 'woocommerce_account_menu_items', array( $this, 'add_wpf_delete_account_tab' ), PHP_INT_MAX );
		add_action( 'woocommerce_account_' . $this->endpoint . '_endpoint', array( $this, 'add_content' ) );
	}

	/**
	 * Register Delete Account Endpoint.
	 */
	public function register_endpoint() {

		add_rewrite_endpoint( $this->endpoint, EP_ROOT | EP_PAGES );
	}

	/** Refresh once after installation or an endpoint change, after all endpoints are registered. */
	public function maybe_refresh_rewrites() {
		if ( get_option( 'wpfda_registered_endpoint' ) !== $this->endpoint ) {
			flush_rewrite_rules( false );
			update_option( 'wpfda_registered_endpoint', $this->endpoint );
		}
	}

	/**
	 * Add new query var.
	 *
	 * @param array $vars vars.
	 *
	 * @return array.
	 */
	public function query_vars( $vars ) {

		$vars[] = $this->endpoint;
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

		if ( ! wpfda_can_delete_account() || true === apply_filters( 'wpfda_disable_delete_account_tab', false ) ) {
			return $items;
		}

		$items[ $this->endpoint ] = get_option( 'wpfda_title', esc_html__( 'Delete Account', 'wp-frontend-delete-account' ) );

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
