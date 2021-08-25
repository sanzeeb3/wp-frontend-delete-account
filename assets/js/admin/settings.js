import { render } from '@wordpress/element';
import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

class Field extends Component {

	constructor(props) {

    	super(props);

    	this.state = {
    		value: props.attr.defaultValue,
    		isChecked: 'on' === props.attr.defaultValue ? true : false,
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

		if ( 'wpfda_security' === event.target.name ) {

			var showId = [];
			var hideId = [];

			switch( event.target.value ) {

				case 'password':

					showId = [ 'security-password' ];
					hideId = [ 'security-custom-captcha-question', 'security-custom-captcha-answer' ];

				break;

				case 'custom_captcha':

					showId = [ 'security-custom-captcha-question', 'security-custom-captcha-answer' ];
					hideId = [ 'security-password' ];

				break;

				default:

					hideId = [ 'security-password', 'security-custom-captcha-question', 'security-custom-captcha-answer' ]
			}

			var newFields = this.props.fields;

			newFields.forEach((item,index)=>{

  				if( showId.includes( item.id ) ) {	// Equivalent to PHP's in_array().
    	  			newFields[index].isShowing = true;
  				}

  				if( hideId.includes( item.id ) ) {
  					newFields[index].isShowing = false;
  				}

			});

			this.props.parentCallback( newFields );
		}
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
				            id={"wpfda-"+ this.props.attr.id}
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
							onChange={this.handleInputChange}
							className={"wpfda-"+ this.props.attr.id}
							>

							<option value="none"> { __( 'None', 'wp-frontend-delete-account' ) } </option>
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

				this.props.attr.isShowing

				?

					<tr valign="top" className={"wp-frontend-delete-account-" + this.props.attr.id}>
						<th scope="row">{this.props.attr.label}</th>
						<td>
							{this.props.attr.type === 'checkbox' ? <input type ="hidden" value="off" name={"wpfda_" + this.props.attr.name}/> : null}
							{element}
							{this.props.attr.type !== 'checkbox' ? <br/> : null }
							<label htmlFor={"wpfda-" + this.props.attr.id}><i>{this.props.attr.desc}</i></label>
						</td>
					</tr>

				:

				null
		);
	}
}

class Form extends Component {

	constructor(props) {
		super(props);

		var users = wpfda_plugins_params.users;

		var attribute_options = [];

		users.map( (user) =>
			attribute_options[attribute_options.length] = { value: user.ID, label: user.data.user_login }
		);

		const fields = [
			{
				id: 'delete-comments',
				name: 'delete_comments',
				label: __( 'Delete Comments', 'wp-frontend-delete-account' ),
				desc: __( 'Delete all comments by users when they delete themselves.', 'wp-frontend-delete-account' ),
				type: 'checkbox',
				defaultValue: wpfda_plugins_params.delete_comments,
				isShowing: true
			},
			{
				id: 'title',
				name: 'title',
				label: __( 'Title', 'wp-frontend-delete-account' ),
				desc: '',
				defaultValue: wpfda_plugins_params.title,
				type: 'text',
				isShowing: true
			},
			{
				id: 'button-label',
				name: 'button_label',
				label: __( 'Button Label', 'wp-frontend-delete-account' ),
				desc: '',
				defaultValue: wpfda_plugins_params.button,
				type: 'text',
				isShowing: true
			},
			{
				id: 'redirect-url',
				name: 'redirect_url',
				label: __( 'Redirect URL', 'wp-frontend-delete-account' ),
				desc: __( 'Leave empty for same page redirect', 'wp-frontend-delete-account' ),
				defaultValue: wpfda_plugins_params.redirect_url,
				type: 'url',
				isShowing: true
			},
			{
				id: 'attribute',
				name: 'attribute',
				label: __( 'Attribute all contents to:', 'wp-frontend-delete-account' ),
				type: 'select',
				options: attribute_options,
				defaultValue: wpfda_plugins_params.attribute,
				isShowing: true
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
				defaultValue: wpfda_plugins_params.security,
				isShowing: true

			},
			{
				id: 'security-password',
				name: 'security_password_text',
				label: __( 'Confirmation Text', 'wp-frontend-delete-account' ),
				defaultValue: wpfda_plugins_params.password_text,
				type: 'text',
				isShowing: wpfda_plugins_params.security === 'password' ? true : false
			},
			{
				id: 'security-custom-captcha-question',
				name: 'security_custom_captcha_question',
				label: __( 'Captcha Question', 'wp-frontend-delete-account' ),
				defaultValue: wpfda_plugins_params.captcha_question,
				type: 'text',
				isShowing: wpfda_plugins_params.security === 'custom_captcha' ? true : false
			},
			{
				id: 'security-custom-captcha-answer',
				name: 'security_custom_captcha_answer',
				label: __( 'Captcha Answer', 'wp-frontend-delete-account' ),
				defaultValue: wpfda_plugins_params.captcha_answer,
				type: 'text',
				isShowing: wpfda_plugins_params.security === 'custom_captcha' ? true : false
			},
		];

		this.state = {
			data: fields
		}

	    this.handleCallback = this.handleCallback.bind(this);
	}

    handleCallback(childData) {
 		this.setState({data: childData})
	}

	render() {

		return (
			<div className="wp-frontend-delete-account-settings">
				<form method="post">
					<table className="form-table">
						<tbody>
							{
								this.state.data.map( (field) =>

									<Field parentCallback={this.handleCallback} key={field.id} attr={field} fields={this.state.data}/>
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
	const appRoot = document.getElementById( 'wp-frontend-delete-account-settings-page' );

	if ( appRoot ) {
		render(
			<Form />,
			appRoot
		)
	}
});
