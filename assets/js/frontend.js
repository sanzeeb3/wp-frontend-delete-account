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
				} else {
  			    	error.html('').append('<i>'+ wda_plugins_params.recaptcha_required +'</i>');
  				    return;
				}
    	    } else if ( 'custom_captcha' === wda_plugins_params.security ) {
    	    	var answer = $('.wda-delete-account-container').find('.wda-custom-captcha').find('input').val();
				if( answer != wda_plugins_params.captcha_answer ) {
					error.html('').append('<i>'+ wda_plugins_params.incorrect_answer +'</i>');
  				    return;
				} else {
					error.html('');
				}

    	    } else if ( 'password' === wda_plugins_params.security ) {
    	    	// Do nothing now. See server side validation.
    	    } else if( '' === wda_plugins_params ) {
    	    	// Do nothing now. See server side validation.
    	    } else {
    	    	error.html( '' ).append( '<i>Something went wrong!</i>' );
    	    }

			var data = {
				action: 'confirm_delete',
				security: wda_plugins_params.wda_nonce,
			};

			$.post( wda_plugins_params.ajax_url, data, function( response ) {
				// Success. Do nothing. Silence is golden.
        	});
    	});
});
