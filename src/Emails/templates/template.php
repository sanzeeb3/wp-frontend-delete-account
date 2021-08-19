<?php
/**
 * The Email template.
 *
 * This template can be overridden by copying it to your-child-theme/wp-frontend-delete-account/template.php.
 *
 * HOWEVER, on occasion WP Frontend Delete Account! will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @since 1.5.8
 *
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$backgound_color = apply_filters( 'wp_frontend_delete_account_summary_email_background_color', '#f7f7f7' );
$footer_text     = apply_filters( 'wp_frontend_delete_account_summary_email_footer_text', sprintf( __( 'This email was auto-generated and sent from <a href="%1$s">%2$s</a>. Learn <a href="%3$s">%4$s</a>.', 'wp-frontend-delete-account' ), get_bloginfo( 'url' ), get_bloginfo(), admin_url( '/options-general.php?page=wp-frontend-delete-account' ), __( 'how to disable it', 'wp-frontend-delete-account' ) ) );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo get_bloginfo(); ?> </title>
		<style type="text/css">
		body {margin: 0; padding: 0; min-width: 100%!important;}
		.content {width: 100%; max-width: 600px;}
		</style>
	</head>
	<body>
		<table width="100%" bgcolor="<?php echo $backgound_color; ?>" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td>
					<table class="content" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td style="padding: 20px">
							   <?php
							   		// Message.
								?>
							</td>
						</tr>
					</table>

					 <table class="content" align="center" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td style="text-align: center">
							   <?php
								echo $footer_text;
								// TODO:: Escape HTML tags in output based on what is accepted.
								?>

							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>
