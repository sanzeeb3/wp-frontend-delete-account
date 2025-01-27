<?php
/**
 * Plugin Name: WP Frontend Delete Account
 * Description: Lets customers delete their account by their own.
 * Version: 2.4.2
 * Author: Mini Plugins
 * Author URI: https://miniplugins.com
 * Text Domain: wp-frontend-delete-account
 * Domain Path: /languages/
 *
 * @package    WP Frontend Delete Account
 * @author     Mini Plugins
 * @since      1.0.0
 * @license    GPL-3.0+
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
	// Exit if accessed directly.
}

if ( function_exists( 'wfda_fs' ) ) {
    wfda_fs()->set_basename( true, __FILE__ );
} else {
    // DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
    if ( ! function_exists( 'wfda_fs' ) ) {
        // Create a helper function for easy SDK access.
        function wfda_fs() {
            global $wfda_fs;
    
            if ( ! isset( $wfda_fs ) ) {
                // Include Freemius SDK.
                require_once dirname(__FILE__) . '/freemius/start.php';
    
                $wfda_fs = fs_dynamic_init( array(
                    'id'                  => '15171',
                    'slug'                => 'wp-frontend-delete-account',
                    'type'                => 'plugin',
                    'public_key'          => 'pk_eb6b8985bfbc6eff4bf462ab818ec',
                    'is_premium'          => true,
                    'premium_suffix'      => 'Pro',
                    // If your plugin is a serviceware, set this option to false.
                    'has_premium_version' => true,
                    'has_addons'          => false,
                    'has_paid_plans'      => true,
                    'menu'                => array(
                        'slug'           => 'wp-frontend-delete-account',
                        'contact'        => false,
                        'support'        => false,
                        'account'        => false,
                        'pricing'        => false,
                        'parent'         => array(
                            'slug' => 'options-general.php',
                        ),
                    ),
                ) );
            }
    
            return $wfda_fs;
        }
    
        // Init Freemius.
        wfda_fs();
        // Signal that SDK was initiated.
        do_action( 'wfda_fs_loaded' );
    }
        
    // Define WPFDA_PLUGIN_FILE.
    if ( ! defined( 'WPFDA_PLUGIN_FILE' ) ) {
        define( 'WPFDA_PLUGIN_FILE', __FILE__ );
    }

    // Plugin Folder Path.
    if ( ! defined( 'WPFDA_PLUGIN_DIR' ) ) {
        define( 'WPFDA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
    }

    /**
     * Plugin version.
     *
     * @var string
     */
    define( 'WPFDA_VERSION', '2.4.2' );

    require_once __DIR__ . '/vendor/autoload.php';

    /**
     * Return the main instance of Plugin Class.
     *
     * @since  1.2.0
     *
     * @return Plugin.
     */
    function wp_frontend_delete_account() {
            $instance = \WPFrontendDeleteAccount\Plugin::get_instance();
            $instance->init();

            return $instance;
    }

    wp_frontend_delete_account();
}