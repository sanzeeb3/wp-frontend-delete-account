jQuery( function( $ ) {
	var security = $('.wda-security');				// Select the security method selector.
	hide_fields( security );						// Hide fields on load.

	$( security ).change( function() {
  		hide_fields( security );					// Hide fields onChange.
	});

	function hide_fields( security ) {

		var value = security.val();		 			// Value of the security method field.
		var tr = security.closest('tr'); 			// Find the closest tr.

		if( value === 'none' ) {
			tr.nextAll().hide();					// Hide all next tr.
		} else if( value === 'password' ) {
			tr.nextAll().hide();					// Hide all next tr.
			tr.next().show();						// SHow only first next tr.
		} else if( value === 'custom_captcha' ) {
			tr.nextAll().hide();					// Hide all next tr.
			tr.next().next().show();
			tr.next().next().next().show();
		} else if( value === 'recaptcha_v2' ) {
			tr.nextAll().hide();					// Hide all next tr.
			tr.next().next().next().next().show();
			tr.next().next().next().next().next().show();
		}
	}
});
