/* global wda_plugins_params
 *
 */
jQuery( function( $ ) {
	jQuery('body').on('click', '.wda-delete-account-container .woo-delete-account-button button', function(e) {
    	    e.preventDefault();

			if( grecaptcha && grecaptcha.getResponse().length > 0 ) {
				// Success. Do nothing.
			} else {

				var error =  $(this).parent().parent().siblings( '.wda-error' ).find('span');
  			    error.html('').append('<i>'+ wda_plugins_params.recaptcha_required +'</i>');
  			    return;
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
