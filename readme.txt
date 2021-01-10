=== WP Frontend Delete Account ===
Contributors: sanzeeb3
Tags: delete-account, delete-account-from-frontend, frontend-delete, remove-account, gdpr, gdpr-erasure
Requires at least: 4.0
Tested up to: 5.6
Requires PHP: 5.6
Stable tag: 1.3.5
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Lets customers delete their account by their own from their myaccount page for WooCommerce sites. For non-store sites, add the account delete page anywhere with shortcode and gutenberg block.

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

== A Gentle reminder of GDPR's Right to be forgotten ==

The right to be forgotten dovetails with people’s right to access their personal information in [Article 15](https://gdpr.eu/article-15-right-of-access/). The right to control one’s data is meaningless if people cannot take action when they no longer consent to processing, when there are significant errors within the data, or if they believe information is being stored unnecessarily. In these cases, an individual can request that the data be erased. 

You have one month to respond to a request. I understand you're busy. Make it easy by allowing users to erase their personal data.

**I am not a lawyer and this is not a legal advice.** In all circumstances I strongly recommend you consult directly with legal counsel familiar with the GDPR regulations.

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

= 1.3.5 - 1/10/2021 =
* Fix - Correctly load autoload file.
* Fix - WooCommerce myaccount tab custom title
* Add - pl_PL translation (props to Jacek)

= 1.3.4 - 11/20/2020 =
* Fix - 404 page when the permanlinkns has yet not been set.

= 1.3.3 - 10/02/2020 =
* 404 page on post name permalinks.

= 1.3.2 - 09/05/2020 =
* Fix - Missing 'Emails' dir.

= 1.3.1 - 09/05/2020 =
* Fix - Error due to small case folder

= 1.3.0 - 09/04/2020 =
* Refactor - Use of autoloading and namespaces.

= 1.2.1 - 07/17/2020 =
* Fix - PHP Notice when page does not exit.

= 1.2.0 - 04/25/2020 =
* Feature - Emails after account deletion.
* Refactor - Use of namespaces and composer autoload.
* Enahancement - Clean unnecessary stuffs.
* Enahancement - Load assets conditionally on backend.
* Requires PHP 5.6 or higher.

= 1.1.2 - 03/30/2020 =
* Enhancement - Render Shortcode content on gutenberg

= 1.1.1 - 03/08/2020 =
* Conditionally load assets

= 1.1.0 - 2/22/2020 =
* Drop reCAPTCHA support

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
