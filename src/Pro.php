<?php

namespace WPFrontendDeleteAccount;

/**
 * All features related to PRO version.
 */
class Pro {

	/**
	 * Initialize.
	 *
	 * @since  2.0.0
	 */
	public function init() {
    }

	/**
	 * Get the Pro Settings.
	 *
	 * @since 2.0.0
	 *
	 * @return array.
	 */
	public function get_settings() {

		global $wp_roles;

		return apply_filters(
			'wp_frontend_delete_account_pro_settings',
			[
				'delete_account_endpoint'        => [
					'id'      => 'wpfda-delete_account_endpoint',
					'name'    => 'wpfda_delete_account_endpoint',
					'type'    => 'input',
                    'default' => 'wpf-delete-account',
					'label'   => __( 'WooCommerce Delete Account Tab Endpoint', 'wp-frontend-delete-account' ),
                    'desc'    => sprintf( __( 'After changing this settings, please refresh the permanlinks by going to %s.', 'wpfda' ), '<a href="'. admin_url( 'options-permalink.php' ) .'">Settings > Permalinks</a>' ),
				],

				'exclude_roles' => [
					'id'      => 'wpfda-exclude-roles',
					'name'    => 'wpfda_exclude_roles',
					'type'    => 'checkbox-multiple',
					'options' => $wp_roles->get_names(),
					'default' => 'off',
					'label'   => __( 'Exclude User Roles', 'wpfda' ),
					'desc'    => __( 'Check the user roles that shouldn\'t see the "Delete Account" tab.', 'wpfda' ),
				],
			]
		);
	}

    	/**
	 * Render Settings.
	 *
	 * @todo Make render settings an abstract - logs settings is also using the same.
	 *
	 * @since 3.20
	 */
	public function render_page() {

		?>	
		<?php do_action( 'wpfda_pro_settings_init' ); ?>
		<h2><?php esc_html_e( 'Advanced Settings', 'wp-frontend-delete-account' ); ?></h2>
		<div class="wpfda-pro-settings-container">
			<div class="wpfda-pro-settings-settings" style="max-width: 80%">
				<form method="post">
					<table class="form-table">
						<?php foreach ( (array) $this->get_settings() as $key => $settings ) : ?>
						<tr valign="top" class="<?php echo esc_attr( $settings['id'] ); ?>">
							<th scope="row"><label for="<?php echo esc_attr( $settings['id'] ); ?>"><?php echo esc_html( $settings['label'] ); ?></label></th>
								<td>
									<?php
									$saved = get_option( $settings['name'] );
									switch ( $settings['type'] ) {
										case 'checkbox':
											$value = isset( $saved ) ? $saved : $settings['default'];
											echo '<fieldset>';
											?>
											<label for="<?php echo esc_attr( $settings['id'] ); ?>" >
												<input type="checkbox"
													id="<?php echo esc_attr( $settings['id'] ); ?>"
													name="<?php echo esc_attr( $settings['name'] ); ?>"
													<?php checked( $value, 'on', true ); ?>
												/>
												<?php esc_html_e( $settings['desc'] ); ?>
											</label>
											<?php
											echo '</fieldset>';
											break;

										case 'checkbox-multiple':
											echo '<fieldset>';
											foreach ( $settings['options'] as $role_value => $role_name ) {

												$value = isset( $saved ) && is_array( $saved ) ? in_array( $role_value, $saved ) : '';
												?>
												<label for="<?php echo $settings['name'] . '_' . esc_attr( $role_value ); ?>" >
													<input type="checkbox"
														id="<?php echo $settings['name'] . '_' . esc_attr( $role_value ); ?>"
														name="<?php echo esc_attr( $settings['name'] ); ?>[]"
														value="<?php echo esc_attr( $role_value ); ?>"
														<?php checked( $value, true, true ); ?>
													/>
													<?php esc_html_e( $role_name ); ?>
												</label></br/>
												<?php
											}
											echo '</fieldset>';
											echo '<br/>';
                                            echo '<i>' . $settings['desc'] . '</i>';
											break;

										default:
											?>
												<input type="<?php echo esc_attr( $settings['type'] ); ?>"
													value="<?php echo !empty( $saved ) ? esc_attr( $saved ) : esc_attr( $settings['default'] ); ?>"
													id="<?php echo esc_attr( $settings['id'] ); ?>"
													name="<?php echo esc_attr( $settings['name'] ); ?>"
												/>
											<?php
                                            echo '<br/>';
                                            echo '<br/>';
   											echo '<i>' . $settings['desc'] . '</i>';
									}//end switch
									?>
								</td>
						</tr>
						<?php endforeach; ?>

					</table>
						<?php wp_nonce_field( 'wp_frontend_delete_account_settings', 'wp_frontend_delete_account_settings_nonce' ); ?>
						<?php submit_button(); ?>
				</form>
			</div>

			<?php echo \wpfda_backend_sidebar(); ?>

			<?php do_action( 'wpfda_pro_settings_after' ); ?>
		</div>
		<?php
	}
}