import { render } from '@wordpress/element';
import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

class Form extends Component {

	render() {
		return (
			<h2>General Settings</h2>
		)
	}

}

document.addEventListener( "DOMContentLoaded", function(event) {

	render(
		<Form />,
		document.getElementsByClassName( 'wpfda-delete-account-container' )[0]
	)
});
