/**
 * View for the block on Block Editor.
 *
 * This is only a view (placeholder). Actual implementation is on ../frontend.js
 *
 * @since 1.5.7
 */
import { __ } from '@wordpress/i18n';

export default function Placeholder() {

	var element = [];

	if ( wpfda_plugins_params.is_administrator ) {
		element = [ <i key='warning' style={{color:'red'}}> { __( 'Just a heads up! You are the site administrator and processing further will delete yourself.', 'wp-frontend-delete-account' ) } </i> ]
	}

	if ( 'password' === wpfda_plugins_params.security ) {
		element = [ ...element,

					<div key='wpfda-password-confirm' className='wpfda-password-confirm'>
						<label> { wpfda_plugins_params.password_text } </label>
						<input type='password' name='wpfda-password' />
					</div>
				]
	}

	if ( 'custom_captcha' === wpfda_plugins_params.security && '' !== wpfda_plugins_params.captcha_question ) {
		element = [ ...element,

					<div key='wpfda-custom-captcha' className='wpfda-custom-captcha'>
						<label> { wpfda_plugins_params.captcha_question } </label>
						<input type='text' name='wpfda-custom-captcha-answer' />
					</div>
				]
	}

	element = [	...element,
				<div key='wpfda-submit' className='wpfda-submit'>
						<button type="submit">{wpfda_plugins_params.button}</button>
				</div>
			]

	return (

		<form>
			{element}
		</form>
	)
}
