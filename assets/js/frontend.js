import { render } from '@wordpress/element';
import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import {useState} from 'react';


function Contents() {

	const processAction = () => {
		setDisable( true );
	}

	var element = [];

	const [disable, setDisable] = useState( false );

	if ( wpfda_plugins_params.is_administrator ) {
		element = [ <i key='warning' style={{color:'red'}}> { __( 'Just a heads up! You are the site administrator and processing further will delete yourself.', 'wp-frontend-delete-account' ) } </i> ]
	}

	if ( 'password' === wpfda_plugins_params.security ) {
		element = [ ...element,

					<div key='wpfda-password-confirm' className='wpfda-password-confirm'>
						<label> { wpfda_plugins_params.password_text } </label>
						<input type='password' name='wpfda-password' />
					</div>
				]
	}

	if ( 'custom_captcha' === wpfda_plugins_params.security && '' !== wpfda_plugins_params.captcha_question ) {
		element = [ ...element,

					<div key='wpfda-custom-captcha' className='wpfda-custom-captcha'>
						<label> { wpfda_plugins_params.captcha_question } </label>
						<input type='text' name='wpfda-custom-captcha-answer' />
					</div>
				]
	}

	element = [ ...element,
				<div key='wpfda-error' className='wpfda-error'>
					<span style={{color:'red'}}></span>
				</div>
			]

	element = [	...element,
				<div key='wpfda-submit' className='wpfda-submit'>
						<button disabled={disable} onClick={processAction}>{wpfda_plugins_params.button}</button>
				</div>
			]

	return element;
}

document.addEventListener( "DOMContentLoaded", function(event) {

	render(
		<Contents />,
		document.getElementsByClassName( 'wpfda-delete-account-container' )[0]
	)
});
