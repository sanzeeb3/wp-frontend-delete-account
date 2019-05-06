<?php
/**
 * @since  1.0.0
 *
 * Class for adding gutenberg block.
 *
 * @class WPFDA_Gutenberg_Block
 */
Class WPFDA_Gutenberg_Block {

	/**
	 * Constructor.
	 */
	public function __construct() {
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
    		plugins_url( 'assets/js/admin/wpfda-gutenberg-block.js', WPFDA_PLUGIN_FILE ),
    		array('wp-blocks','wp-editor'),
    		true
  		);
	}

	/**
	 * Register gutenber block.
	 *
	 * @return Void.
	 */
	public function register_block() {

		if( ! function_exists( 'register_block_type' ) ) {
			return;
		}

	    register_block_type( 'wp-frontend-delete-account/delete-account-content', array(
	        'editor_script' 	=> 'wpfda-gutenberg-block',
	        'render_callback' 	=> 'wpf_delete_account_content',
	    ) );
	}
}

new WPFDA_Gutenberg_Block();
