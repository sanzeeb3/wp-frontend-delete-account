<?php
namespace WPFrontendDeleteAccount\Pro;

/** Elementor integration. */
class Widget extends \Elementor\Widget_Base {
	public function get_name() { return 'wp-frontend-delete-account'; }
	public function get_title() { return esc_html__( 'WP Frontend Delete Account', 'wp-frontend-delete-account' ); }
	public function get_icon() { return 'eicon-trash-o'; }
	public function get_categories() { return array( 'basic' ); }
	public function get_keywords() { return array( 'delete', 'account' ); }
	protected function render() { echo do_shortcode( '[wp_frontend_delete_account]' ); }
	public function render_plain_content() { echo '[wp_frontend_delete_account]'; }
}
