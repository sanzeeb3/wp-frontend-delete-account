<?php

namespace WPFrontendDeleteAccount\Elementor;

use Elementor\Widget_Base;
use Elementor\Plugin as ElementorPlugin;

/**
 * Elementor WP Frontend Delete Account Widget.
 *
 * @since 2.1.2
 */
class Widget extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve WP Frontend Delete Account widget name.
	 *
	 * @since 2.1.2
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wp-frontend-delete-account';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve WP Frontend Delete Account widget title.
	 *
	 * @since 2.1.2
	 * @access public
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'WP Frontend Delete Account', 'wp-frontend-delete-account' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve WP Frontend Delete Account widget icon.
	 *
	 * @since 2.1.2
	 * @access public
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-trash-o';
	}

	/**
	 * Get custom help URL.
	 *
	 * Retrieve a URL where the user can get more information about the widget.
	 *
	 * @since 2.1.2
	 * @access public
	 * @return string Widget help URL.
	 */
	public function get_custom_help_url() {
		return 'https://sanjeebaryal.com.np/how-to-allow-users-to-delete-their-account-from-frontend#elementor';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the WP Frontend Delete Account widget belongs to.
	 *
	 * @since 2.1.2
	 * @access public
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'basic' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the WP Frontend Delete Account widget belongs to.
	 *
	 * @since 2.1.2
	 * @access public
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'delete', 'account' ];
	}

	/**
	 * Render WP Frontend Delete Account widget output.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 2.1.2
	 * @access protected
	 */
	protected function render() {

		if ( ElementorPlugin::$instance->editor->is_edit_mode() ) {
			$this->render_edit_mode();
		} else {
			$this->render_frontend();
		}
	}

	/**
	 * Render widget output in edit mode.
	 *
	 * @since 2.1.2
	 */
	protected function render_edit_mode() {

		// Render in frontend anyway, because we don't render anything on edit mode yet.
		echo do_shortcode( $this->render_shortcode() );
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * @since 2.1.2
	 */
	protected function render_frontend() {

		echo do_shortcode( $this->render_shortcode() );
	}

	/**
	 * Render widget as plain content.
	 *
	 * @since 2.1.2
	 */
	public function render_plain_content() {

		echo $this->render_shortcode(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Render shortcode.
	 *
	 * @since 2.1.2
	 */
	public function render_shortcode() {

		return '[wp_frontend_delete_account]';
	}
}
