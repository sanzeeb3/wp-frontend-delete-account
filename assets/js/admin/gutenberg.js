/**
 * The block.
 *
 * @since 1.5.7 Edit and Save changes.
 *
 */
const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

import Placeholder from './placeholder';

registerBlockType( 'wp-frontend-delete-account/delete-account-content', {
    title: __( 'WP Frontend Account Delete', 'wp-frontend-delete-account' ),
    icon: 'admin-users',
    category: 'common',
    attributes: {
        images : {
            default: [],
            type:   'array',

        }
    },
	edit: Placeholder,

	/**
	 * Save()
	 */
    Placeholder,
} );
