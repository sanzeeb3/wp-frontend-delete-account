/**
 * Frontend JS. The feedback email is performed in jQuery, rest in react.
 *
 * @since 1.5.7
 *
 * global jQuery, wpfda_plugin_params
 */

const { render, useState } = wp.element;
const { __ } = wp.i18n;

export default function Contents() {

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

				var value = captchaValue;

			break;
		}

		// Validation is complete at this point. Let's process the deletion now.
		setErrorText( wpfda_plugins_params.processing );

		var data = {
			action: 'confirm_delete',
			security: wpfda_plugins_params.wpfda_nonce,
			value: value,
			acknowledged: acknowledged ? '1' : '',
		};

		jQuery.post( wpfda_plugins_params.ajax_url, data, function( response ) {

			if( response.success === false ) {
				setErrorText( response.data.message );
				setDisable( false );

				return;
			} else if( response.success === true ) {

				setErrorText( response.message );

				if ( 'on' !==  wpfda_plugins_params.is_feedback_enabled ) {

					window.location.replace( wpfda_plugins_params.redirect_url || window.location.href );
				} else {

					var data = {
						action: 'delete_feedback',
						feedback_token: response.feedback_token,
					};
					var feedbackToken = response.feedback_token;

					jQuery.post( wpfda_plugins_params.ajax_url, data, function( response ) {

						jQuery('body').append( response );
						var modal = document.getElementById('wp-frontend-delete-account-modal');

				  		// Open the modal.
				  		modal.style.display = "block";

				  		// On click on send email button on the modal.
						jQuery('.wpfda-skip-feedback').on('click', function(e) {
							e.preventDefault();
							window.location.replace( wpfda_plugins_params.redirect_url || window.location.href );
						});
						jQuery("#wp-frontend-delete-account-send-deactivation-email").on('submit', function( e ) {
							e.preventDefault();

							var button = jQuery('#wpfda-send-deactivation-email');
							button.val( wpfda_plugins_params.deleting ).prop( 'disabled', true );

					    	var form 		= jQuery("#wp-frontend-delete-account-send-deactivation-email");

							var message		= form.find( ".row .col-75 textarea#message" ).val();
							var nonce 		= form.find( ".row #wpfda_delete_feedback_email").val();

							var data = {
								action: 'delete_feedback_email',
								security: nonce,
								message: message,
								feedback_token: feedbackToken
							}

							jQuery.post( wpfda_plugins_params.ajax_url, data, function( response ) {

								if( response.success === false ) {
									alert( wpfda_plugins_params.wrong );
									button.prop( 'disabled', false );
								} else {
									window.location.replace( wpfda_plugins_params.redirect_url || window.location.href );
								}
							}).fail( function( xhr ) {
								alert( wpfda_plugins_params.wrong );
								button.prop( 'disabled', false );
							});

					    });

					}).fail( function( xhr ) {
						window.location.replace( wpfda_plugins_params.redirect_url || window.location.href );
					});
				}
			}
		}).fail( function() {
			setErrorText( wpfda_plugins_params.wrong );
			setDisable( false );
		});
	}

	var element = [];

	const [disable, setDisable] = useState( false );
	const [errorText, setErrorText] = useState( '' );
	const [passwordValue, setPasswordValue] = useState( '' );
	const [captchaValue, setCaptchaValue] = useState( '' );
	const [acknowledged, setAcknowledged] = useState( false );

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

	if ( wpfda_plugins_params.require_acknowledgement ) {
		element = [ ...element, <label key="acknowledgement"><input type="checkbox" required checked={acknowledged} onChange={e => setAcknowledged(e.target.checked)} /> {wpfda_plugins_params.acknowledgement_text}</label> ];
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

	Array.prototype.forEach.call( document.getElementsByClassName( 'wpfda-delete-account-container' ), function( container ) {
		render( <Contents />, container );
	} );
});
