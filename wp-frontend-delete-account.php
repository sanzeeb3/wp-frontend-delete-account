<?php
/**
 * Plugin Name: WP Frontend Delete Account
 * Description: Lets customers delete their account by their own.
 * Version: 3.0.0
 * Requires at least: 5.3
 * Requires PHP: 5.6
 * Author: Mini Plugins
 * Author URI: https://miniplugins.com
 * Text Domain: wp-frontend-delete-account
 * Domain Path: /languages/
 * @fs_premium_only /src/Pro/
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
                if ( file_exists( __DIR__ . '/vendor/freemius/start.php' ) ) {
                    require_once __DIR__ . '/vendor/freemius/start.php';
                } else {
                    // Allow existing installations with the previous SDK location.
                    require_once __DIR__ . '/freemius/start.php';
                }

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

    define( 'WPFDA_VERSION', '3.0.0' );

    register_activation_hook( __FILE__, function () {
        delete_option( 'wpfda_registered_endpoint' );
    } );

    // The release archive must not depend on an ignored Composer vendor directory.
    spl_autoload_register( function ( $class ) {
        $prefix = 'WPFrontendDeleteAccount\\';
        if ( 0 !== strpos( $class, $prefix ) ) {
            return;
        }
        $file = __DIR__ . '/src/' . str_replace( '\\', '/', substr( $class, strlen( $prefix ) ) ) . '.php';
        if ( is_file( $file ) ) {
            require_once $file;
        }
    } );
    require_once __DIR__ . '/functions.php';

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
