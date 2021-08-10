/* global wpfda_plugins_params
 *
 * Popup modal is adapted from w3schools.
 *
 * @link https://www.w3schools.com/howto/howto_css_modals.asp
*/
jQuery( function( $ ) {

	$('body').on('click', '.wpfda-enable-disable', function(e) {

		if( $(this).hasClass( 'wpfda-enable' ) ) {
			$(this).removeClass('wpfda-enable').addClass('wpfda-disable');
			$(this).removeAttr('title').attr( 'title', wpfda_plugins_params.enable_email );
			var enable = 0;
		} else {
			$(this).removeClass('wpfda-disable').addClass('wpfda-enable');
			$(this).removeAttr('title').attr( 'title', wpfda_plugins_params.disable_email );
			var enable = 1;
		}

		var email = $(this).attr('data-email');

		var data = {
			action: 'wpfda_email_status',
			email: email,
			enable: enable,
			security: wpfda_plugins_params.status_nonce
		}

		$.post( wpfda_plugins_params.ajax_url, data, function( response ) {
			// Success. Do nothing. Silence is golden.
	    });
	});
});
