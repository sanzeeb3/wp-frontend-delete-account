=== WP Frontend Delete Account ===
Contributors: miniplugins3, sanzeeb3
Tags: delete-account, delete-account-from-frontend, frontend-delete, remove-account, gdpr, gdpr-erasure
Requires at least: 5.0
Tested up to: 6.7
Requires PHP: 5.6
Stable tag: 6.0.4
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Lets customers delete their account by their own from their myaccount page for WooCommerce sites. For non-store sites, add the account delete page anywhere with shortcode and gutenberg block.

== Description ==

Lets customers delete their account by their own from their myaccount page for WooCommerce sites. New 'Delete Account' tab will be created automatically in myaccount page.

For non-store sites, add the 'Delete Account' tab content anywhere with shortcode [wp_frontend_delete_account] or with the built-in gutenberg block.

Find out more on [setup documentation](https://miniplugins.com/how-to-allow-users-to-delete-their-account-from-frontend/)

### Features:
* Easy account deletion from the WooCommerce myaccount page.
* Reassigns posts by the deleted user.
* Option to delete user comments.
* Gutenberg block and shortcode ([wp_frontend_delete_account]) support.
* GDPR compliance for user data erasure.
* Customize delete account (wpf-delete-account) endpoint [PRO]
* Exclude user roles [PRO]
* Elementor Widget [PRO]

[Upgrade to PRO](https://miniplugins.com/wp-frontend-delete-account/)

== A Gentle reminder on GDPR's Right to be forgotten ==

The right to be forgotten dovetails with people’s right to access their personal information in [Article 15](https://gdpr.eu/article-15-right-of-access/). The right to control one’s data is meaningless if people cannot take action when they no longer consent to processing, when there are significant errors within the data, or if they believe information is being stored unnecessarily. In these cases, an individual can request that the data be erased. 

You have one month to respond to a request. I understand you're busy. Make it easy by allowing users to erase their personal data.

**I am not a lawyer and this is not a legal advice.** In all circumstances I strongly recommend you consult directly with legal counsel familiar with the GDPR regulations.

### What's Next?
If you like WP Frontend Delete Account plugin, then consider checking out my other projects:
- [Birthday For WooCommerce](https://miniplugins.com/birthday-for-woocommerce-premium/) - celebrate your customers birthday in style.
- [WooCommerce Auto Delete Old Orders](https://miniplugins.com/woocommerce-auto-delete-old-orders/) - Automatically delete old WooCommerce orders.
- [WooCommerce Auto Delete Users With No Orders](https://miniplugins.com/wc-auto-delete-users-with-no-orders/) - Automatically delete users with no purchase history.
- [WooCommerce Customer Journey](https://miniplugins.com/customer-journey-for-woocommerce/) - Displays every step of the customer until the order of the product in the orders page.

== Frequently Asked Questions ==

= Can I translate WP Frontend Delete Account?

Yes. The plugin is translation ready. You can use any tranlstion plugin. Or customize the plugins language with the provided pot file. The recommended way is to help WP Frontend Delete Account by contributing the translation on [WordPress.org](https://translate.wordpress.org/projects/wp-plugins/wp-frontend-delete-account).

= I see 404 error: page not found?

You might need to refresh the permalinks. Navigate to Settings->Permalinks and Save.

= I accidently deleted my own account. I am the owner of the site.

Unfortunately, there is no any way to recover the deleted user. To create a new user and get access to the site follow: [This Great Tutorial](https://www.wpbeginner.com/wp-tutorials/how-to-add-an-admin-user-to-the-wordpress-database-via-mysql/)

== Screenshots ==

1. My Account Delete Account View
2. Using Shortcode View
3. Gutenberg Block View

== Changelog ==

= 2.4.1 - 12/8/2024 =
* Fix - Image icon

= 2.4.0 - 12/7/2024 =
* Tweaks - Freemius integration code snippet
* Info - Tested upto WP 6.7

= 2.2.0 - 05/27/2024 =
* Fix - Flush permalinks to fix 404 issues on static pages

= 2.1.0 - 03/26/2024 =
* Fix - JSX translations issue
* Tweak - Submenu & sidebar

= 2.0.0 - 03/14/2024 =
* New - Customize "wpf-delete-account" endpoint
* New - Exclude specified user roles
* Info - Tested upto WP 6.4

= 1.7.0 - 10/13/2022 =
* Fix - Error when shortcode is used twice on the same page.
* Fix - URL issue if Redirect URL is empty.
* Fix - "Confirm" button not translatable
* Fix - Exclude dev vendors from distribution

= 1.6.1 - 02/19/2022 =
* Fix - debug code log (https://wordpress.org/support/topic/error-message-in-logs-2/)

= 1.6.0 - 01/24/2022 =
* Fix - Compatiblity with block themes.
* Fix - Email summary task should not be scheduled if not enabled.

= 1.5.9 - 09/23/2021 =
* Optimize - Users query

= 1.5.8 - 08/26/2021 =
* Feature - Weekly summary email
* Add - Review notice
* Deprecate - load assets globally option

= 1.5.7 - 08/19/2021 =
* Enhancement - Remove unnecessary difficulties on plugin deactivation.
* Enhancement - Refactor frontend part.

= 1.5.6 - 08/08/2021 =
* Enhancement - Ensure 'Delete Account' will be the last item in the WC account menu.
* Enhancement - Refactor settings page, transistioning into react.

= 1.5.5 - 07/22/2021 =
* Fix - Compatibility with WP 5.5 widgets screen

= 1.5.4.1 - 05/25/2021 =
* Fix - Minifed JS allowing multiple button clicks.

= 1.5.4 - 05/24/2021 =
* Fix - Duplicate emails when brute force clicks on the button.
* Enhancement - Remove unnecssary review notice and recommended plugins.

= 1.5.3 - 04/28/2021 =
* Feature - Optinally delete comments

= 1.5.2 - 04/06/2021 =
* Enhancement - Remove plugin data on plugin delete
* Fix - Duplicate feedback emails being sent

= 1.5.1 - 03/24/2021 =
* Fix - Only receive data when relevant.

= 1.5.0 - 03/19/2021 =
* Add - WooCommerce Email Templates for sites using WooCommerce.
* Add - Recommended plugins.

= 1.4.2 - 02/24/2021 =
* Fix - Issue with JS minification when debug mode is off.

= 1.4.1 - 02/24/2021 =
* Fix - undefined variable on plugin deactivation.

= 1.4.0 - 02/23/2021 =
* Feature - Redirect URL after account delete.
* Feature - Feedback email after account delete.
* Tweak - Send plugin deactivation feedback to site.

= 1.3.6 - 1/21/2021 =
* Feature - Editor field for email message.

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
