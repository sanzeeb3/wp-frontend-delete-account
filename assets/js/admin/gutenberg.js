const { registerBlockType } = wp.blocks;
import { __ } from '@wordpress/i18n';

import Contents from '../frontend';


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
	edit: Contents,

	/**
	 * Save()
	 */
    Contents,
} );
