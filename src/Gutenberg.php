<?php

namespace WPFrontendDeleteAccount;

/**
 * Class for adding gutenberg block.
 *
 * @since  1.0.0
 *
 * @since 1.2.0, changed class name from WPFDA_Gutenberg_Block to Gutenberg with Namespace.
 *
 * @class Gutenberg
 */
class Gutenberg {

	/**
	 * Initialize.
	 *
	 * @since  1.3.0 Change Constructor to init.
	 */
	public function init() {

		add_action( 'init', array( $this, 'register_block' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'load_assets' ) );
	}

	/**
	 * Load assets on gutenberg area.
	 *
	 * @return void.
	 */
	public function load_assets() {

		wp_enqueue_script(
			'wpfda-gutenberg-block',
			plugins_url( 'assets/js/admin/gutenberg.min.js', WPFDA_PLUGIN_FILE ),
			array( 'wp-blocks', 'wp-i18n', 'wp-element' ),
			WPFDA_VERSION,
			true
		);

		wp_localize_script(
			'wpfda-gutenberg-block',
			'wpfda_plugins_params',
			\wpfda_i10n_data()
		);
	}

	/**
	 * Register gutenber block.
	 *
	 * @return Void.
	 */
	public function register_block() {

		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		register_block_type(
			'wp-frontend-delete-account/delete-account-content',
			array(
				'editor_script'   => 'wpfda-gutenberg-block',
				'render_callback' => 'wpf_delete_account_content',
			)
		);
	}
}
