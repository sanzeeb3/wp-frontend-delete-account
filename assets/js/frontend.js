/* global wda_plugins_params
 *
 */
jQuery( function( $ ) {
	jQuery('body').on('click', '.wda-delete-account-container .woo-delete-account-button button', function(e) {
    	    e.preventDefault();
			var error =  $(this).parent().parent().siblings( '.wda-error' ).find('span');

    	    if( 'recaptcha_v2' === wda_plugins_params.security ) {
   				if( grecaptcha && grecaptcha.getResponse().length > 0 ) {
					error.html('');
					value = grecaptcha.getResponse();
				} else {
  			    	error.html('').append('<i>'+ wda_plugins_params.recaptcha_required +'</i>');
  				    return;
				}
    	    } else if ( 'custom_captcha' === wda_plugins_params.security ) {
    	    	var value = $('.wda-delete-account-container').find('.wda-custom-captcha').find('input').val();
				if( value != wda_plugins_params.captcha_answer ) {
					error.html('').append('<i>'+ wda_plugins_params.incorrect_answer +'</i>');
  				    return;
				} else {
					error.html('');
				}

    	    } else if ( 'password' === wda_plugins_params.security ) {
    	    	value = $('.wda-delete-account-container').find('.wda-password-confirm').find('input').val();

    	    	if( value == '' ) {
    	    		error.html('').append('<i>'+ wda_plugins_params.empty_password +'</i>');
    	    		return;
    	    	} else {
					error.html('');
				}
    	    }

			var data = {
				action: 'confirm_delete',
				security: wda_plugins_params.wda_nonce,
				value: value,
			};

			$.post( wda_plugins_params.ajax_url, data, function( response ) {
				if( response.success === false ) {
					error.html('').append('<i>'+ response.data.message +'</i>');
					return;
				}
        	});
    	});
});
