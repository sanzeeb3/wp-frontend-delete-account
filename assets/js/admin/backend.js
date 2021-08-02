import { render } from '@wordpress/element';
import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

class Field extends Component {

	constructor(props) {
    	super(props);

    	this.state = {
    		value: props.attr.defaultValue,
    		isChecked: 'on' === props.attr.defaultValue ? true : false
    	};

	    this.handleInputChange = this.handleInputChange.bind(this);
  	}

	handleInputChange(event) {
	    this.setState(
	    	{
	    		value: event.target.value,
	    		isChecked: event.target.checked,
	    	}
	    );
	}

	render() {

		var element = '';

		switch(this.props.attr.type) {
			case 'text':
				element = <input
							type="text"
							onChange={this.handleInputChange}
							name={"wpfda_" + this.props.attr.name}
							value={this.state.value}
							className={"wp-frontend-delete-account-" + this.props.attr.id + "-inline"}
						/>;
			break;

			case 'url':
				element = <input
							type="url"
							onChange={this.handleInputChange}
							name={"wpfda_" + this.props.attr.name}
							value={this.state.value}
							className={"wp-frontend-delete-account-" + this.props.attr.id + "-inline"}
						/>;
			break;

			case 'checkbox':
				element = <input
							type="checkbox"
							onChange={this.handleInputChange}
							checked={this.state.isChecked}
							name={"wpfda_" + this.props.attr.name}
							className={"wp-frontend-delete-account-" + this.props.attr.id + "-inline"}
						/>;
			break;

			case 'select':
				element = <select
							name={"wpfda_" + this.props.attr.name }
							value={this.state.value}
							onChange={this.handleInputChange}>

							<option> { __( 'None', 'wp-frontend-delete-account' ) } </option>
							{
								this.props.attr.options.map( (option) =>
									<option key={option.value} value={option.value}>{option.label}</option>
								)
							}

							</select>
			break;

			default:
		}

		return (

			<tr valign="top" className={"wp-frontend-delete-account-" + this.props.attr.id}>
				<th scope="row">{this.props.attr.label}</th>
				<td>
					{this.props.attr.type === 'checkbox' ? <input type ="hidden" value="off" name={"wpfda_" + this.props.attr.name}/> : null}
					{element}
					{this.props.attr.type !== 'checkbox' ? <br/> : null }
					<i>{this.props.attr.desc}</i>
				</td>
			</tr>
		);
	}
}

class Form extends Component {
	render() {

		var users = wpfda_plugins_params.users;

		var attribute_options = [];

		users.map( (user) =>
			attribute_options[attribute_options.length] = { value: user.ID, label: user.data.user_login }
		);

		const fields = [
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
				defaultValue: wpfda_plugins_params.title,
				type: 'text'
			},
			{
				id: 'button-label',
				name: 'button_label',
				label: __( 'Button Label', 'wp-frontend-delete-account' ),
				desc: '',
				defaultValue: wpfda_plugins_params.button,
				type: 'text'
			},
			{
				id: 'redirect-url',
				name: 'redirect_url',
				label: __( 'Redirect URL', 'wp-frontend-delete-account' ),
				desc: __( 'Leave empty for same page redirect', 'wp-frontend-delete-account' ),
				defaultValue: wpfda_plugins_params.redirect_url,
				type: 'url'
			},
			{
				id: 'attribute',
				name: 'attribute',
				label: __( 'Attribute all contents to:', 'wp-frontend-delete-account' ),
				type: 'select',
				options: attribute_options,
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
				defaultValue: wpfda_plugins_params.password_text,
				type: 'text'
			},
			{
				id: 'security-custom-captcha-question',
				name: 'security_custom_captcha_question',
				label: __( 'Captcha Question', 'wp-frontend-delete-account' ),
				defaultValue: wpfda_plugins_params.captcha_question,
				type: 'text'
			},
			{
				id: 'security-custom-captcha-answer',
				name: 'security_custom_captcha_answer',
				label: __( 'Captcha Answer', 'wp-frontend-delete-account' ),
				defaultValue: wpfda_plugins_params.captcha_answer,
				type: 'text'
			},
		];

		return (
			<div className="wp-frontend-delete-account-settings">
				<form method="post">
					<table className="form-table">
						<tbody>
							{
								fields.map( (field) =>

									<Field key={field.id} attr={field} />
								)
							}
						</tbody>
					</table>

					<input type="hidden" id="wpfda-general-settings-save-nonce" name="wp_frontend_delete_account_settings_nonce" value={wpfda_plugins_params.wpfda_general_settings_nonce} />
					<p className="submit"><input type="submit" name="submit" id="submit" className="button button-primary button-large" value="Save Changes" /></p>

				</form>
			</div>
		)
	}
}

document.addEventListener( "DOMContentLoaded", function(event) {
	render(
		<Form />,
		document.getElementById( 'wp-frontend-delete-account-settings-page' )
	)
});
