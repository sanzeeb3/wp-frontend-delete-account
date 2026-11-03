import './review';
const { render, Component } = wp.element;
const { __ } = wp.i18n;

class Field extends Component {

	constructor(props) {

    	super(props);

    	this.state = {
    		value: props.attr.defaultValue,
    		isChecked: 'on' === props.attr.defaultValue ? true : false,
    	};

	    this.handleInputChange = this.handleInputChange.bind(this);
		this.searchUsers = this.searchUsers.bind(this);
  	}

	searchUsers(event) {
		const search = event.target.value.trim();
		clearTimeout(this.searchTimer);
		const sequence = this.searchSequence = (this.searchSequence || 0) + 1;
		if (search.length < 2) return;
		this.searchTimer = setTimeout(() => {
			const url = wpfda_plugins_params.ajax_url + '?action=wpfda_search_users&security=' + encodeURIComponent(wpfda_plugins_params.wpfda_general_settings_nonce) + '&search=' + encodeURIComponent(search);
			fetch(url).then(response => response.json()).then(response => {
				if (sequence !== this.searchSequence || !response.success) return;
				const selected = (this.state.options || this.props.attr.options).filter(option => String(option.value) === String(this.state.value));
				const options = response.data.map(user => ({value: user.ID, label: user.user_login}));
				this.setState({options: selected.concat(options.filter(option => !selected.some(item => String(item.value) === String(option.value))))});
			}).catch(() => {});
		}, 250);
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
			case 'textarea':
				element = <textarea name={"wpfda_" + this.props.attr.name} value={this.state.value} onChange={this.handleInputChange} rows="5" className="large-text" />;
			break;
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
				element = <div>{this.props.attr.name === 'attribute' ? <div><input type="search" onChange={this.searchUsers} placeholder={__( 'Search users by name or email', 'wp-frontend-delete-account' )} /></div> : null}<select
							name={"wpfda_" + this.props.attr.name }
							value={this.state.value}
							onChange={this.handleInputChange}
							className={"wpfda-"+ this.props.attr.id}
							>

							<option value="none"> { __( 'None', 'wp-frontend-delete-account' ) } </option>
							{
								(this.state.options || this.props.attr.options).map( (option) =>
									<option key={option.value} value={option.value}>{option.label}</option>
								)
							}

							</select></div>
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
			attribute_options[attribute_options.length] = { value: user.ID, label: user.user_login }
		);

		const fields = [
			{
				id: 'before-content', name: 'before_content',
				label: __( 'Content before the deletion form', 'wp-frontend-delete-account' ),
				desc: __( 'Explain what account deletion means. Basic HTML is allowed.', 'wp-frontend-delete-account' ),
				type: 'textarea', defaultValue: wpfda_plugins_params.before_content, isShowing: true
			},
			{
				id: 'require-acknowledgement', name: 'require_acknowledgement',
				label: __( 'Require acknowledgement', 'wp-frontend-delete-account' ),
				desc: __( 'Require users to confirm they understand deletion is permanent.', 'wp-frontend-delete-account' ),
				type: 'checkbox', defaultValue: wpfda_plugins_params.require_acknowledgement, isShowing: true
			},
			{
				id: 'acknowledgement-text', name: 'acknowledgement_text',
				label: __( 'Acknowledgement text', 'wp-frontend-delete-account' ),
				type: 'text', defaultValue: wpfda_plugins_params.acknowledgement_text, isShowing: true
			},
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
