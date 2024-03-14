/**
 * Review Js.
 *
 * Global jQuery, wpfda_plugins_params
 *
 * @since 1.5.8
 */

import { render } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { useState } from '@wordpress/element';

export default function Review() {

	const [dismiss, setDismiss] = useState( wpfda_plugins_params.upgrade_notice_dismissed );
	const dismissReview = (e) => {
		setDismiss( 'yes' );

		var data = {
			action: 'wp_frontend_delete_account_dismiss_review_notice',
			security: wpfda_plugins_params.review_nonce,
			dismissed: true,
		};

		jQuery.post( wpfda_plugins_params.ajax_url, data, function( response ) {
			// Success. Do nothing. Silence is golden.
    	});
	}

	return (

		dismiss === 'no'

		?
			<div id ='wp-frontend-delete-account-review-notice' className='notice notice-info wp-frontend-delete-account-review-notice'>
				<div className ='wp-frontend-delete-account-review-thumbnail'>
					<img src={wpfda_plugins_params.image_url} alt='' />
				</div>

				<div className='wp-frontend-delete-account-review-text'>
					<a onClick={dismissReview} id='wp-frontend-delete-account-dismiss' href='#'>x</a>
					<h3> { __( 'Whoopee! 😀', 'wp-frontend-delete-account' ) } </h3>
					<p>{ __( 'How\'s it going? I hope that you found WP Frontend Delete Account helpful. ', 'wp-frontend-delete-account' ) }<br/><br/>
					<p>{ __( 'WP Frontend Delete Account PRO ⭐ is now avaialble with more powerful features.') }</p>
						<a href={wpfda_plugins_params.pricing_page_url}>{__( 'Upgrade Now', 'wp-frontend-delete-account')} </a>
					</p>
				</div>
			</div>

		:

		null
	)
}

document.addEventListener( "DOMContentLoaded", function(event) {
	const appRoot = document.getElementById( 'root' );

	if ( appRoot ) {
		render(
			<Review />,
			appRoot
		)
	}
});
