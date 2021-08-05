/* global wpfda_plugins_params
 *
 * Popup modal is adapted from w3schools.
 *
 * @link https://www.w3schools.com/howto/howto_css_modals.asp
*/
jQuery( function( $ ) {
	var security = $('.wpfda-security');			// Select the security method selector.
	hide_fields( security );						// Hide fields on load.

console.log( security );
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


// @TODO::Separate js to load only when needed.
jQuery(document).ready(function( $ ){
	// Deactivation feedback.
 	$( document.body ).on( 'click' ,'tr[data-plugin="wp-frontend-delete-account/wp-frontend-delete-account.php"] span.deactivate a', function( e ) {
		e.preventDefault();

		var data = {
			action: 'wpfda_deactivation_notice',
			security: wpfda_plugins_params.deactivation_nonce
		};

		$.post( wpfda_plugins_params.ajax_url, data, function( response ) {
			jQuery('#wpbody-content .wrap').append( response );
			var modal = document.getElementById('wp-frontend-delete-account-modal');

	  		// Open the modal.
	  		modal.style.display = "block";

	  		// On click on send email button on the modal.
		    $("#wpfda-send-deactivation-email").click( function( e ) {
		    	e.preventDefault();

		    	this.value 		= wpfda_plugins_params.deactivating;
		    	var form 		= $("#wp-frontend-delete-account-send-deactivation-email");

				var message		= form.find( ".row .col-75 textarea#message" ).val();
				var nonce 		= form.find( ".row #wpfda_deactivation_email").val();

				var data = {
					action: 'wpfda_deactivation_email',
					security: nonce,
					message: message,
				}

				$.post( wpfda_plugins_params.ajax_url, data, function( response ) {

					if( response.success === false ) {
						alert( wpfda_plugins_params.wrong, response.data.message, "error" );
					} else {
						location.reload();
					}

					modal.remove();
				}).fail( function( xhr ) {
					alert( wpfda_plugins_params.error, wpfda_plugins_params.wrong, "error" );
				});

		    });

		}).fail( function( xhr ) {
			window.console.log( xhr.responseText );
		});
 	});
});
