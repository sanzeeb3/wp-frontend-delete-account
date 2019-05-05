/* global wpfda_plugins_params
 *
 */
jQuery( function( $ ) {
	jQuery('body').on('click', '.wpfda-delete-account-container .wpf-delete-account-button button', function(e) {
    	    e.preventDefault();
			var error =  $(this).parent().parent().siblings( '.wpfda-error' ).find('span');

    	    if( 'recaptcha_v2' === wpfda_plugins_params.security ) {
   				if( grecaptcha && grecaptcha.getResponse().length > 0 ) {
					error.html('').append('<i>'+ wpfda_plugins_params.processing +'</i>');
					value = grecaptcha.getResponse();
				} else {
  			    	error.html('').append('<i>'+ wpfda_plugins_params.recaptcha_required +'</i>');
  				    return;
				}
    	    } else if ( 'custom_captcha' === wpfda_plugins_params.security ) {
    	    	var value = $('.wpfda-delete-account-container').find('.wpfda-custom-captcha').find('input').val();
				if( value != wpfda_plugins_params.captcha_answer ) {
					error.html('').append('<i>'+ wpfda_plugins_params.incorrect_answer +'</i>');
  				    return;
				} else {
					error.html('').append('<i>'+ wpfda_plugins_params.processing +'</i>');
				}

    	    } else if ( 'password' === wpfda_plugins_params.security ) {
    	    	value = $('.wpfda-delete-account-container').find('.wpfda-password-confirm').find('input').val();

    	    	if( value == '' ) {
    	    		error.html('').append('<i>'+ wpfda_plugins_params.empty_password +'</i>');
    	    		return;
    	    	} else {
					error.html('').append('<i>'+ wpfda_plugins_params.processing +'</i>');
				}
    	    }

			var data = {
				action: 'confirm_delete',
				security: wpfda_plugins_params.wpfda_nonce,
				value: value,
			};

			$.post( wpfda_plugins_params.ajax_url, data, function( response ) {

				if( response.success === false ) {
					error.html('').append('<i>'+ response.data.message +'</i>');
					return;
				} else if( response.success === true ) {
					error.html('').append('<i>'+ response.message +'</i>');
					location.reload();
				}
        	});
    	});
});
