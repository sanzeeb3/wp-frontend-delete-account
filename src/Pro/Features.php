<?php
namespace WPFrontendDeleteAccount\Pro;

/** Premium features, removed from the Freemius free build. */
class Features {
	public function init() {
		add_filter( 'wpfda_can_delete_account', array( $this, 'can_delete' ), 10, 2 );
		add_filter( 'wpfda_woocommerce_endpoint', array( $this, 'endpoint' ) );
		add_action( 'wpfda_settings_tabs', array( $this, 'settings_tab' ) );
		add_action( 'wpfda_settings_section', array( $this, 'settings_section' ) );
		add_action( 'admin_init', array( $this, 'save_settings' ) );
		add_action( 'elementor/widgets/register', array( $this, 'register_widget' ) );
	}

	public function can_delete( $allowed, $user ) {
		$excluded = (array) get_option( 'wpfda_exclude_roles', array() );
		return $allowed && ! array_intersect( (array) $user->roles, $excluded );
	}

	public function endpoint( $default ) {
		$endpoint = sanitize_title( get_option( 'wpfda_delete_account_endpoint', $default ) );
		return $endpoint ? $endpoint : $default;
	}

	public function settings_tab( $section ) {
		$url = add_query_arg( array( 'page' => 'wp-frontend-delete-account', 'section' => 'advanced' ), admin_url( 'options-general.php' ) );
		echo '<a href="' . esc_url( $url ) . '" class="nav-tab ' . ( 'advanced' === $section ? 'nav-tab-active' : '' ) . '">' . esc_html__( 'Advanced', 'wp-frontend-delete-account' ) . '</a>';
	}

	public function settings_section( $section ) {
		if ( 'advanced' !== $section ) {
			return;
		}
		$roles = wp_roles()->get_names();
		$excluded = (array) get_option( 'wpfda_exclude_roles', array() );
		?>
		<h2><?php esc_html_e( 'Advanced Settings', 'wp-frontend-delete-account' ); ?></h2>
		<form method="post">
			<?php wp_nonce_field( 'wpfda_advanced_settings', 'wpfda_advanced_nonce' ); ?>
			<table class="form-table"><tbody>
			<tr><th scope="row"><label for="wpfda-endpoint"><?php esc_html_e( 'WooCommerce Delete Account Tab Endpoint', 'wp-frontend-delete-account' ); ?></label></th>
			<td><input id="wpfda-endpoint" name="wpfda_delete_account_endpoint" type="text" value="<?php echo esc_attr( $this->endpoint( 'wpf-delete-account' ) ); ?>"></td></tr>
			<tr><th scope="row"><?php esc_html_e( 'Exclude User Roles', 'wp-frontend-delete-account' ); ?></th><td><fieldset>
			<?php foreach ( $roles as $role => $name ) : ?>
				<label><input type="checkbox" name="wpfda_exclude_roles[]" value="<?php echo esc_attr( $role ); ?>" <?php checked( in_array( $role, $excluded, true ) ); ?>> <?php echo esc_html( translate_user_role( $name ) ); ?></label><br>
			<?php endforeach; ?>
			<p class="description"><?php esc_html_e( 'Users with any selected role cannot delete their accounts through this plugin.', 'wp-frontend-delete-account' ); ?></p>
			</fieldset></td></tr></tbody></table>
			<?php submit_button(); ?>
		</form>
		<?php
	}

	public function save_settings() {
		if ( ! isset( $_POST['wpfda_advanced_nonce'] ) ) {
			return;
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You are not allowed to change these settings.', 'wp-frontend-delete-account' ), '', array( 'response' => 403 ) );
		}
		check_admin_referer( 'wpfda_advanced_settings', 'wpfda_advanced_nonce' );
		$roles = isset( $_POST['wpfda_exclude_roles'] ) && is_array( $_POST['wpfda_exclude_roles'] ) ? array_map( 'sanitize_key', wp_unslash( $_POST['wpfda_exclude_roles'] ) ) : array();
		update_option( 'wpfda_exclude_roles', array_values( array_intersect( $roles, array_keys( wp_roles()->get_names() ) ) ) );
		$endpoint = isset( $_POST['wpfda_delete_account_endpoint'] ) ? sanitize_title( wp_unslash( $_POST['wpfda_delete_account_endpoint'] ) ) : '';
		$endpoint = $endpoint ? $endpoint : 'wpf-delete-account';
		if ( $endpoint !== $this->endpoint( 'wpf-delete-account' ) ) {
			update_option( 'wpfda_delete_account_endpoint', $endpoint );
			delete_option( 'rewrite_rules' );
		}
	}

	public function register_widget( $manager ) {
		if ( class_exists( '\\Elementor\\Widget_Base' ) ) {
			$manager->register( new Widget() );
		}
	}
}
