<?php

namespace WPFrontendDeleteAccount\Elementor;

/**
 * Elementor Compatibility.
 *
 * @since 2.1.2
 */
class Elementor {

	/**
	 * Are we ready?
	 *
	 * @since 2.1.2
	 *
	 * @return bool
	 */
	public function allow_load() {

		return (bool) did_action( 'elementor/loaded' );
	}

	/**
	 * Initialize.
	 *
	 * @since 2.1.2.
	 */
	public function init() {

		if ( ! $this->allow_load() || ! class_exists( '\Elementor\Plugin' ) ) {
			return;
		}

		add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'load_scripts' ) );
		add_action( 'elementor/widgets/register', array( $this, 'register_widget' ) );
	}

	/**
	 * Load required scripts on Elementor pages.
	 *
	 * @since 2.1.2
	 */
	public function load_scripts() {

	}

	/**
	 * Register AAWP Widget.
	 *
	 * @since 2.1.2
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 * @return void
	 */
	public function register_widget( $widgets_manager ) {

		include_once WPFDA_PLUGIN_DIR . 'src/Elementor/Widget.php';

		$widgets_manager->register( new Widget() );
	}
}
