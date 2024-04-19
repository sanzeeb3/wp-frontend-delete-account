=== WP Frontend Delete Account ===
Contributors: sanzeeb3
Tags: delete-account, delete-account-from-frontend, frontend-delete, remove-account, gdpr, gdpr-erasure
Requires at least: 4.9
Tested up to: 6.5
Requires PHP: 5.6
Stable tag: 2.1.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Lets customers delete their account by their own from their myaccount page for WooCommerce sites. For non-store sites, add the account delete page anywhere with shortcode and gutenberg block.

== Description ==

WP Frontend Delete Account is a powerful and user-friendly plugin that allows customers to delete their account from the myaccount page in WooCommerce sites with ease. It offers a seamless and intuitive way for users to manage their accounts, enhancing the overall user experience.

One of the key features of WP Frontend Delete Account is its ability to automatically add a 'Delete Account' tab to the myaccount page. This tab provides users with a straightforward process to delete their account, eliminating the need for complex procedures or contacting site administrators.

For non-store sites, WP Frontend Delete Account provides a shortcode and Gutenberg block, making it easy to add the 'Delete Account' tab content anywhere on the site. This flexibility allows site owners to customize the user experience and meet the specific needs of their audience.

A major concern for users deleting their accounts is the impact on their content, such as posts and comments. WP Frontend Delete Account addresses this concern by providing an option to reassign posts by the deleted user. This ensures that valuable content is not lost and can continue to contribute to the site's content.

Additionally, WP Frontend Delete Account offers the option to delete user comments, providing users with a comprehensive account deletion process. This feature is particularly useful for sites with user-generated content, ensuring that deleted accounts do not leave behind unwanted comments.

WP Frontend Delete Account is fully compliant with GDPR regulations regarding the right to erasure. It allows users to delete their account and provides options for reassigning posts and deleting comments, ensuring compliance with GDPR's requirements.

### Feature and Options:
* Easy account deletion from the myaccount page.
* Reassigns posts by the deleted user.
* Option to delete user comments.
* Gutenberg block and shortcode ([wp_frontend_delete_account]) support.
* GDPR compliance for user data erasure.

Find out more on [setup documentation](https://sanjeebaryal.com.np/how-to-allow-users-to-delete-their-account-from-frontend/)

== ⭐ PRO Version Features ==
* Customize delete account (wpf-delete-account) endpoint
* Exclude user roles
* Elementor Widget

[Upgrade to PRO](https://sanjeebaryal.com.np/how-to-allow-users-to-delete-their-account-from-frontend#pricing)

== A Gentle reminder on GDPR's Right to be forgotten ==

The right to be forgotten dovetails with people’s right to access their personal information in [Article 15](https://gdpr.eu/article-15-right-of-access/). The right to control one’s data is meaningless if people cannot take action when they no longer consent to processing, when there are significant errors within the data, or if they believe information is being stored unnecessarily. In these cases, an individual can request that the data be erased. 

You have one month to respond to a request. I understand you're busy. Make it easy by allowing users to erase their personal data.

**I am not a lawyer and this is not a legal advice.** In all circumstances I strongly recommend you consult directly with legal counsel familiar with the GDPR regulations.

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

= 2.1.0 - 03/26/2024 =
* Fix - JSX translations issue
* Tweak - Submenu & sidebar

= 2.0.0 - 03/14/2024 =
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
