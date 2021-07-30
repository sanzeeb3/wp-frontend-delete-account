import { render } from '@wordpress/element';
import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

class Field extends Component {

	render() {

		return (

			<tr key="">
			</tr>
		);
	}
}

class Settings extends Component {
	render() {

		const attrs = [
			{
				id: 'load-assets-globally',
				name: 'load_assets_globally',
				label: __( 'Load Assets Globally', 'wp-frontend-delete-account' ),
				desc: __( 'Check this if only you have compatibility/styling issues', 'wp-frontend-delete-account' ),
				type: 'checkbox',
				defaultValue: wpfda_plugins_params.load_assets
			},
			{
				id: 'delete-comments',
				name: 'delete_comments',
				label: __( 'Delete Comments', 'wp-frontend-delete-account' ),
				desc: __( 'Delete all comments by users when they delete themselves.', 'wp-frontend-delete-account' ),
				type: 'checkbox',
				defaultValue: wpfda_plugins_params.delete_comments
			},
			{
				id: 'title',
				name: 'title',
				label: __( 'Title', 'wp-frontend-delete-account' ),
				desc: '',
				value: wpfda_plugins_params.title,
				type: 'text'
			},
			{
				id: 'button-label',
				name: 'button_label',
				label: __( 'Button Label', 'wp-frontend-delete-account' ),
				desc: '',
				value: wpfda_plugins_params.button,
				type: 'text'
			},
			{
				id: 'redirect-url',
				name: 'redirect_url',
				label: __( 'Redirect URL', 'wp-frontend-delete-account' ),
				desc: __( 'Leave empty for same page redirect', 'wp-frontend-delete-account' ),
				value: wpfda_plugins_params.redirect_url,
				type: 'url'
			},
			{
				id: 'attribue',
				name: 'attribue',
				label: __( 'Attribute all contents to:', 'wp-frontend-delete-account' ),
				type: 'select',
				options: wpfda_plugins_params.users,
				defaultValue: wpfda_plugins_params.attribute
			},
			{
				id: 'security',
				name: 'security',
				label: __( 'Security method before deleting:', 'wp-frontend-delete-account' ),
				type: 'select',
				options: [
					{
						value: 'password',
						label: __( 'Password', 'wp-frontend-delete-account' )
					},

					{
						value: 'custom_captcha',
						label: __( 'Custom Captcha', 'wp-frontend-delete-account' )
					}
				],
				defaultValue: wpfda_plugins_params.security
			},
			{
				id: 'security-password',
				name: 'security_password_text',
				label: __( 'Confirmation Text', 'wp-frontend-delete-account' ),
				value: wpfda_plugins_params.password_text,
				type: 'text'
			},
			{
				id: 'security-custom-captcha-question',
				name: 'security_custom_captcha_question',
				label: __( 'Captcha Question', 'wp-frontend-delete-account' ),
				value: wpfda_plugins_params.captcha_question,
				type: 'text'
			},
			{
				id: 'security-custom-captcha-answer',
				name: 'security_custom_captcha_answer',
				label: __( 'Captcha Answer', 'wp-frontend-delete-account' ),
				value: wpfda_plugins_params.captcha_answer,
				type: 'text'
			},
		];

		return (
			<div className="wp-frontend-delete-account-settings">
				<form method="POST">
					<table className="form-table">
						<tbody>
							{
								attrs.map( (attr) =>

									<Field attr={attr} />
								)
							}
						</tbody>
					</table>
				</form>
			</div>
		)
	}
}

document.addEventListener( "DOMContentLoaded", function(event) {
	render(
		<Settings />,
		document.getElementById( 'wp-frontend-delete-account-settings-page' )
	)
});
