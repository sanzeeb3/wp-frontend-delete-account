=== WP Frontend Delete Account ===
Contributors: sanzeeb3
Tags: delete-account, delete account from frontend, frontend delete, remove account,
Requires at least: 4.0
Tested up to: 5.2.0
Requires PHP: 5.3
Stable tag: 1.0.2
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Lets customers delete thier account by their own from their myaccount page for WooCommerce sites. For non-store sites, add the account delete page anywhere with shortcode and gutenberg block.

== Description ==

Lets customers delete thier account by their own from their myaccount page for WooCommerce sites. New 'Delete Account' tab will be created automatically in myaccount page.

For non-store sites, add the 'Delete Account' tab content anywhere with shortcode [wp_frontend_delete_account] or with the built-in gutenberg block.

# Feature and Options:
* Confirm password, custom captcha and reCaptcha protection.
* Reassign the posts by deleted user.
* Delete domain cookies set by user.
* Gutenberg block.
* Shortcode [wp_frontend_delete_account]

[Contribute On GitHub Repository](https://github.com/sanzeeb3/wp-frontend-delete-account)

[Contact The Author](http://www.sanjeebaryal.com.np)

== Frequently Asked Questions ==

= Can I translate WP Frontend Delete Account?

Yes. The plugin is translation ready. You can use any tranlstion plugin. Or customize the plugins language with the provided pot file. The recommended way is to help WP Frontend Delete Account by contributing the translation on [WordPress.org](https://translate.wordpress.org/projects/wp-plugins/wp-frontend-delete-account).

= I see page not found while clicking delete account tab?

You might need to refresh the permalinks. Navigate to Settings->Permalinks and Save.

= I accidently deleted my own account. I am the owner of the site.

Unfortunately, there is not any way to recover the deleted user. To create a new user and get access to the site follow: [This Great Tutorial](https://www.wpbeginner.com/wp-tutorials/how-to-add-an-admin-user-to-the-wordpress-database-via-mysql/)

== Screenshots ==

1. My Account Delete Account View
2. Password Confirmation
3. Custom Captcha
4. reCaptcha
5. Using Shortcode View
6. Gutenberg Block View
7. Settings

== Changelog == 

= 1.0.2 - 05/27/2019 =
* Tweak - Headsup for administrators

= 1.0.1 - 05/08/2019 =
* Fix - Broken functionality due to missing class.
* Fix - WooCommerce myaccount tab echo content instead of return.
* Fix - Misplaced before content hook.
* Fix - Allow delete if no security method is chosen.
* Add - Feedback on plugin deactivation.

= 1.0.0 - 05/07/2019 =
* Initial Release
