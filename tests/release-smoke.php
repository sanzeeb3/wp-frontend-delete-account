<?php
/** Standalone smoke tests; no site database or live accounts are touched. */
define( 'ABSPATH', __DIR__ . '/fixtures/' );
define( 'HOUR_IN_SECONDS', 3600 );
define( 'EP_ROOT', 1 );
define( 'EP_PAGES', 2 );
$options = array();
$transients = array();
$filters = array();
$user = (object) array( 'ID' => 7, 'roles' => array( 'subscriber' ), 'user_email' => 'user@example.com', 'data' => (object) array( 'user_pass' => 'hash', 'user_login' => 'user', 'user_email' => 'user@example.com' ) );
$deleted = false;
$reassigned = null;

class JsonResponse extends Exception { public $data; public function __construct( $data ) { $this->data = $data; } }
function add_filter( $name, $callback, $priority = 10, $accepted = 1 ) { global $filters; $filters[$name][] = $callback; }
function add_action( $name, $callback, $priority = 10, $accepted = 1 ) { add_filter( $name, $callback, $priority, $accepted ); }
function apply_filters( $name, $value ) { global $filters; $args = func_get_args(); array_shift( $args ); foreach ( isset( $filters[$name] ) ? $filters[$name] : array() as $callback ) { $args[0] = call_user_func_array( $callback, $args ); } return $args[0]; }
function do_action() {}
function is_user_logged_in() { return true; }
function wp_get_current_user() { global $user; return $user; }
function get_current_user_id() { return 7; }
function get_user_by( $field, $id ) { global $user; return 7 === (int) $id ? $user : ( 9 === (int) $id ? (object) array( 'ID' => 9 ) : false ); }
function get_option( $key, $default = false ) { global $options; return array_key_exists( $key, $options ) ? $options[$key] : $default; }
function update_option( $key, $value ) { global $options; $options[$key] = $value; }
function delete_option( $key ) { global $options; unset( $options[$key] ); }
function current_user_can() { return true; }
function check_admin_referer() {}
function sanitize_key( $value ) { return strtolower( preg_replace( '/[^a-z0-9_\-]/', '', $value ) ); }
function wp_roles() { return new SmokeRoles(); }
function add_rewrite_endpoint( $endpoint, $mask ) { $GLOBALS['registered_endpoint'] = $endpoint; }
function flush_rewrite_rules( $hard = true ) { $GLOBALS['rewrite_flushes'][] = $hard; }
class SmokeRoles { public function get_names() { return array( 'subscriber' => 'Subscriber', 'editor' => 'Editor' ); } }
function wp_unslash( $v ) { return $v; }
function wp_verify_nonce( $value, $action ) { return 'valid' === $value; }
function wp_check_password( $pass ) { return 'correct' === $pass; }
function sanitize_text_field( $v ) { return is_string( $v ) ? trim( $v ) : ''; }
function sanitize_textarea_field( $v ) { return sanitize_text_field( $v ); }
function sanitize_title( $v ) { return strtolower( trim( $v ) ); }
function __( $v ) { return $v; }
function esc_html__( $v ) { return $v; }
function wp_send_json_error( $data = null, $status = null ) { throw new JsonResponse( array( 'success' => false, 'data' => $data, 'status' => $status ) ); }
function wp_send_json_success( $data = null ) { throw new JsonResponse( array( 'success' => true, 'data' => $data ) ); }
function wp_send_json( $data ) { throw new JsonResponse( $data ); }
function wp_delete_user( $id, $reassign ) { global $deleted, $reassigned; $reassigned = $reassign; return $deleted; }
function wp_clear_auth_cookie() {}
function wp_generate_password() { return 'a-really-long-random-token'; }
function set_transient( $key, $value, $expiry ) { global $transients; $transients[$key] = $value; }
function get_transient( $key ) { global $transients; return isset( $transients[$key] ) ? $transients[$key] : false; }
function delete_transient( $key ) { global $transients; unset( $transients[$key] ); }
function wp_mail( $to, $subject, $message, $headers ) { $GLOBALS['mail_subject'] = $subject; return true; }
function expect( $condition, $message ) { if ( ! $condition ) { throw new Exception( $message ); } }
function response( $callback ) { try { $callback(); } catch ( JsonResponse $response ) { return $response->data; } throw new Exception( 'Expected JSON response' ); }

require dirname( __DIR__ ) . '/functions.php';
require dirname( __DIR__ ) . '/src/Frontend.php';
require dirname( __DIR__ ) . '/src/Pro/Features.php';
require dirname( __DIR__ ) . '/src/WooCommerce.php';
$frontend = new \WPFrontendDeleteAccount\Frontend();
$pro = new \WPFrontendDeleteAccount\Pro\Features();
$pro->init();

$_POST = array( 'wpfda_advanced_nonce' => 'valid', 'wpfda_exclude_roles' => array( 'subscriber', 'nonexistent' ), 'wpfda_delete_account_endpoint' => 'close-account' );
$pro->save_settings();
expect( array( 'subscriber' ) === $options['wpfda_exclude_roles'], 'Only valid roles must be saved' );
expect( 'close-account' === $pro->endpoint( 'wpf-delete-account' ), 'Custom endpoint must be saved' );
$_POST = array( 'wpfda_advanced_nonce' => 'valid', 'wpfda_delete_account_endpoint' => '' );
$pro->save_settings();
expect( array() === $options['wpfda_exclude_roles'], 'Clearing every excluded role must be saved' );
expect( 'wpf-delete-account' === $pro->endpoint( 'wpf-delete-account' ), 'Empty endpoint must use the default' );

$_POST = array( 'security' => 'valid', 'value' => 'correct' );
$options['wpfda_exclude_roles'] = array( 'subscriber' );
expect( ! response( array( $frontend, 'confirm_delete' ) )['success'], 'Excluded roles must be blocked server-side' );
$options['wpfda_exclude_roles'] = array();
$options['wpfda_require_acknowledgement'] = 'on';
expect( ! response( array( $frontend, 'confirm_delete' ) )['success'], 'Acknowledgement must be enforced server-side' );
$_POST['acknowledged'] = '1';
$_POST['security'] = 'invalid';
expect( ! response( array( $frontend, 'confirm_delete' ) )['success'], 'Invalid nonce must be rejected' );
$_POST['security'] = 'valid';
$options['wpfda_security'] = 'custom_captcha';
$options['wpfda_security_custom_captcha_answer'] = 'secret';
$_POST['value'] = 'wrong';
expect( ! response( array( $frontend, 'confirm_delete' ) )['success'], 'Incorrect custom answer must be rejected' );
$options['wpfda_security_custom_captcha_answer'] = '';
$_POST['value'] = '';
expect( ! response( array( $frontend, 'confirm_delete' ) )['success'], 'An empty custom answer must not authorize deletion' );
$options['wpfda_security'] = 'password';
$_POST['value'] = 'correct';
$options['wpfda_attribute'] = 9;
expect( ! response( array( $frontend, 'confirm_delete' ) )['success'], 'Failed deletion must report failure' );
expect( 9 === $reassigned, 'Valid reassignment must be passed through' );
$deleted = true;
$options['wpfda_attribute'] = 7;
expect( ! response( array( $frontend, 'confirm_delete' ) )['success'], 'Self reassignment must fail safely' );
$options['wpfda_attribute'] = 0;
$options['wpfda_enable_feedback_email'] = 'on';
$options['wpfda_enable_user_email'] = 'off';
$options['wpfda_enable_admin_email'] = 'off';
$result = response( array( $frontend, 'confirm_delete' ) );
expect( $result['success'] && ! empty( $result['feedback_token'] ) && 0 === $reassigned, 'Successful deletion must issue a feedback token and preserve existing None reassignment behavior' );
$_POST = array( 'security' => 'valid', 'message' => 'Feedback', 'feedback_token' => $result['feedback_token'], 'user_email' => 'spoof@example.com' );
expect( response( array( $frontend, 'delete_feedback_email' ) )['success'], 'Feedback must send successfully' );
expect( false !== strpos( $GLOBALS['mail_subject'], 'user@example.com' ) && false === strpos( $GLOBALS['mail_subject'], 'spoof@example.com' ), 'Feedback must use the deleted user email, not caller input' );
expect( ! response( array( $frontend, 'delete_feedback_email' ) )['success'], 'Feedback token must be single-use' );
define( 'WC_VERSION', 'test' );
$woocommerce = new \WPFrontendDeleteAccount\WooCommerce();
$woocommerce->init();
$woocommerce->register_endpoint();
$woocommerce->maybe_refresh_rewrites();
$woocommerce->maybe_refresh_rewrites();
expect( 'wpf-delete-account' === $GLOBALS['registered_endpoint'], 'The WooCommerce endpoint must be registered' );
expect( array( false ) === $GLOBALS['rewrite_flushes'], 'Rewrite rules must refresh once without changing permalinks' );
$options['wpfda_delete_account_endpoint'] = 'new-endpoint';
$woocommerce = new \WPFrontendDeleteAccount\WooCommerce();
$woocommerce->init();
$woocommerce->register_endpoint();
$woocommerce->maybe_refresh_rewrites();
expect( 'new-endpoint' === $GLOBALS['registered_endpoint'] && array( false, false ) === $GLOBALS['rewrite_flushes'], 'Endpoint changes must refresh rewrite rules' );
echo "Release smoke tests passed.\n";
