<?php
/**
 * @since  1.0.0
 *
 * Class for adding gutenberg block.
 *
 * @class WDA_Gutenberg_Block
 */
Class WDA_Gutenberg_Block {

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
    		'wda-gutenberg-block',
    		plugins_url( 'assets/js/admin/wda-gutenberg-block.js', WOO_DELETE_ACCOUNT_PLUGIN_FILE ),
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

	    register_block_type( 'woo-delete-account/wda-gutenberg-block', array(
	        'editor_script' 	=> 'wda-gutenberg-block',
	        'render_callback' 	=> 'woo_delete_account_content',
	    ) );
	}
}

new WDA_Gutenberg_Block();
