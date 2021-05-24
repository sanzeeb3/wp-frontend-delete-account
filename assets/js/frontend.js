/* global wpfda_plugins_params
 *
 */
jQuery( function( $ ) {
	jQuery('body').on('click', '.wpfda-delete-account-container .wpf-delete-account-button button', function(e) {
    	    e.preventDefault();
			$btn = this;
			$btn.disabled = true;

			var error =  $(this).parent().parent().siblings( '.wpfda-error' ).find('span');

    	    if( 'custom_captcha' === wpfda_plugins_params.security ) {
    	    	var value = $('.wpfda-delete-account-container').find('.wpfda-custom-captcha').find('input').val();
				if( value != wpfda_plugins_params.captcha_answer ) {
					error.html('').append('<i>'+ wpfda_plugins_params.incorrect_answer +'</i>');
					$btn.disabled = false;
					return;
				} else {
					error.html('').append('<i>'+ wpfda_plugins_params.processing +'</i>');
				}

    	    } else if ( 'password' === wpfda_plugins_params.security || 'recaptcha_v2' ===  wpfda_plugins_params.security ) { // Backwards compatibility. Removing reCAPTCHA support since 1.1.0.
    	    	value = $('.wpfda-delete-account-container').find('.wpfda-password-confirm').find('input').val();

    	    	if( value == '' ) {
    	    		error.html('').append('<i>'+ wpfda_plugins_params.empty_password +'</i>');
					$btn.disabled = false;
					return;
    	    	} else {
					error.html('').append('<i>'+ wpfda_plugins_params.processing +'</i>');
				}
    	    } else if( 'none' === wpfda_plugins_params.security ) {
    	    	error.html('').append('<i>'+ wpfda_plugins_params.processing +'</i>');
    	    }

			var data = {
				action: 'confirm_delete',
				security: wpfda_plugins_params.wpfda_nonce,
				value: value,
			};

			$.post( wpfda_plugins_params.ajax_url, data, function( response ) {

				if( response.success === false ) {
					error.html('').append('<i>'+ response.data.message +'</i>');
					$btn.disabled = false;
					return;
				} else if( response.success === true ) {
					error.html('').append('<i>'+ response.message +'</i>');

					if ( wpfda_plugins_params.is_feedback_enabled === 'on' ) {
						e.preventDefault();

						var data = {
							action: 'delete_feedback',
						};

						$.post( wpfda_plugins_params.ajax_url, data, function( response ) {

							jQuery('body').append( response );
							var modal = document.getElementById('wp-frontend-delete-account-modal');

					  		// Open the modal.
					  		modal.style.display = "block";

					  		// On click on send email button on the modal.
						    $("#wpfda-send-deactivation-email").click( function( e ) {

						    	e.preventDefault();

						    	this.value 		= wpfda_plugins_params.deleting;
								this.disabled   = true;	// Disable the feedback button once email is sent to avoid duplicates.

						    	var form 		= $("#wp-frontend-delete-account-send-deactivation-email");

								var message		= form.find( ".row .col-75 textarea#message" ).val();
								var nonce 		= form.find( ".row #wpfda_delete_feedback_email").val();

								var data = {
									action: 'delete_feedback_email',
									security: nonce,
									message: message,
									user_email: wpfda_plugins_params.current_user_email
								}

								$.post( wpfda_plugins_params.ajax_url, data, function( response ) {

									if( response.success === false ) {
										alert( wpfda_plugins_params.wrong );
									} else {
											window.location.replace( wpfda_plugins_params.redirect_url );
									}

									modal.remove();
								}).fail( function( xhr ) {
									alert( wpfda_plugins_params.wrong );
								});

						    });

						}).fail( function( xhr ) {
							window.console.log( xhr.responseText );
						});
					} else {

						// Redirect if no feedback email is enabled.
						window.location.replace( wpfda_plugins_params.redirect_url );
					}
				}
        	});
    	});
});
