import { render } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

export default function Review() {

	return (
		<div>
		</div>
	)
}

document.addEventListener( "DOMContentLoaded", function(event) {
	const appRoot = document.getElementById( 'wp-frontend-delete-account-review-notice' );

	if ( appRoot ) {
		render(
			<Review />,
			appRoot
		)
	}
});
