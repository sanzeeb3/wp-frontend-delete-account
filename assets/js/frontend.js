/**
 * global jQuery, wpfda_plugin_params
 */

import { render } from '@wordpress/element';
import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { useState } from 'react';

function Contents() {

	const changeValue = (e) => {
		if (e.target.name == 'wpfda-password') {
			setPasswordValue(e.target.value);
		}

		if (e.target.name == 'wpfda-custom-captcha-answer') {
			setCaptchaValue(e.target.value);
		}
	}

	const handleSubmit = (e) => {

		e.preventDefault();
		setDisable( true );

		switch( wpfda_plugins_params.security ) {

			case 'password':

				if ( '' === passwordValue ) {
					setErrorText( wpfda_plugins_params.empty_password );
					setDisable( false );

					return;
				}

				var value = passwordValue;

			break;

			case 'custom_captcha':

				if ( wpfda_plugins_params.captcha_answer != captchaValue ) {
					setErrorText( wpfda_plugins_params.incorrect_answer );
					setDisable( false );

					return;
				}

				var value = captchaValue;

			break;
		}

		// Validation is complete at this point. Let's process the deletion now.
		setErrorText( wpfda_plugins_params.processing );

		var data = {
			action: 'confirm_delete',
			security: wpfda_plugins_params.wpfda_nonce,
			value: value,
		};

		jQuery.post( wpfda_plugins_params.ajax_url, data, function( response ) {

			if( response.success === false ) {
				setErrorText( response.data.message );
				setDisable( false );

				return;
			}

			setErrorText( response.message );

			window.location.replace( wpfda_plugins_params.redirect_url );

		});
	}

	var element = [];

	const [disable, setDisable] = useState( false );
	const [errorText, setErrorText] = useState( '' );
	const [passwordValue, setPasswordValue] = useState( '' );
	const [captchaValue, setCaptchaValue] = useState( '' );

	if ( wpfda_plugins_params.is_administrator ) {
		element = [ <i key='warning' style={{color:'red'}}> { __( 'Just a heads up! You are the site administrator and processing further will delete yourself.', 'wp-frontend-delete-account' ) } </i> ]
	}

	if ( 'password' === wpfda_plugins_params.security ) {
		element = [ ...element,

					<div key='wpfda-password-confirm' className='wpfda-password-confirm'>
						<label> { wpfda_plugins_params.password_text } </label>
						<input onChange={changeValue} type='password' name='wpfda-password' />
					</div>
				]
	}

	if ( 'custom_captcha' === wpfda_plugins_params.security && '' !== wpfda_plugins_params.captcha_question ) {
		element = [ ...element,

					<div key='wpfda-custom-captcha' className='wpfda-custom-captcha'>
						<label> { wpfda_plugins_params.captcha_question } </label>
						<input onChange={changeValue} type='text' name='wpfda-custom-captcha-answer' />
					</div>
				]
	}

	element = [ ...element,
				<div key='wpfda-error' className='wpfda-error'>
					<span style={{color:'red'}}>
						<i>
							{errorText}
						</i>
					</span>
				</div>
			]

	element = [	...element,
				<div key='wpfda-submit' className='wpfda-submit'>
						<button type="submit" disabled={disable}>{wpfda_plugins_params.button}</button>
				</div>
			]

	return (

			<form onSubmit={handleSubmit}>
				{element}
			</form>
	)
}

document.addEventListener( "DOMContentLoaded", function(event) {

	render(
		<Contents />,
		document.getElementsByClassName( 'wpfda-delete-account-container' )[0]
	)
});
