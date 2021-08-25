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

	const [dismiss, setDismiss] = useState( 'off' );

	const dismissReview = (e) => {
		setDismiss( 'on' );
	}

	return (

		dismiss === 'off'

		?
			<div id ='wp-frontend-delete-account-review-notice' className='notice notice-info wp-frontend-delete-account-review-notice'>
				<div className ='wp-frontend-delete-account-review-thumbnail'>
					<img src={wpfda_plugins_params.image_url} alt='' />
				</div>

				<div className='wp-frontend-delete-account-review-text'>
					<a onClick={dismissReview} id='wp-frontend-delete-account-dismiss' href='#'>{ __( 'Dismiss', 'wp-frontend-delete-account') }</a>
					<h3> { __( 'Whoopee!', 'wp-frontend-delete-account' ) } </h3>
					<p>How's it going? I hope that you found WP Frontend Delete Account helpful.<br/><br/>
					Would you do me some favour and leave a  <a href="https://wordpress.org/support/plugin/wp-frontend-delete-account/reviews/?filter=5#new-post" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733; </a>review on <a href="https://wordpress.org/support/plugin/wp-frontend-delete-account/reviews/?filter=5#new-post" target="_blank"><strong>WordPress.org</strong></a> to help us spread the word and boost my motivation?</p>
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
