=== Delete Account - For WooCommerce and all ===
Contributors: sanzeeb3
Tags: entries, wpforms, wpforms-entries, wpforms-data, entry-store,
Requires at least: 4.0
Tested up to: 5.0
Requires PHP: 5.3
Required WC: 3.5.0
Stable tag: 1.4.7
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Lets customers delete thier account by own from their myaccount page.

== Description ==

### STORE AND DISPLAY THE ENTRIES OF WPFORMS LITE PLUGIN

All your WPForms entries are stored in your WordPress database and are easily accessible from inside your WordPress dashboard. If you have multiple forms, you can easily sort through entries by each form. Also, send emails with entries details to users/admin at any time on a single button click, will be helpful if in any case user/admin couldnot receive emails during form submission. You can display the entries on frontend as well with shortcode [wpforms_entries id="x"].

Note that since v1.4.7, new separate menu is created instead of WPForms submenu.

[Contribute On GitHub Repository](https://github.com/sanzeeb3/entries-for-wpforms)

[Contact The Author](http://www.sanjeebaryal.com.np)

### Features And Options:

* Form Entries Storage And Display
* CSV Export
* Geolocation Data Entry And Save
* Resend Emails With Submitted Entries From Entries Panel
* Star Entries
* Mark Entries as read/unread
* Entries Display On Frontend
* IP Address, Browser Details Store
* GDPR Compliant (Consult your lawyer to be 100% certain)
* Well Documented
* Translation ready

### Checkout Fancy Fields For WPForms:

[Fancy Fields For WPForms](https://wordpress.org/plugins/fancy-fields-for-wpforms/) - You can now add file upload and other fancy fields including date, website, divider in your form.


== Frequently Asked Questions ==

= Can I translate Entries For WPForms?

Yes. The plugin is translation ready. You can use any tranlstion plugin. Or customize the plugins language with the provided pot file. The recommended way is to help Entries For WPForms by contributing the translation on [WordPress.org](https://translate.wordpress.org/projects/wp-plugins/entries-for-wpforms).

= Is Entries For WPForms GDPR Complaint?

Yes. You can disable storing users ip addresses and geolocation data. Also, export and erase the users submission upon their request. However, consult your lawyer to be 100% certain.

= What's Next?

Nothing much. I expect WPForms team to include the entries panel in lite version, so that users may not need to use this plugin anymore.


== Screenshots ==

1. Entires Columns
2. Entries Detail View
3. Entries Side view
4. Geolocation Entry Display
5. Frontend Display
6. Send Email

== Changelog == 

= 1.4.7 - = 10/03/2019 =
* Tweak - Move Entries out of WPForms menu.
* Add - GDPR related settings.
* Fix - Sorting, star, read.

= 1.4.6 - 01/03/2019 =
* Fix - Possible authenticated SQL injection attack.
* Fix - Sortable columns.
* Fix - Entries search.
* Tweak - Hide unrelated notices on entries page. 

= 1.4.5 - 22/02/2019 =
* Refactor - Layout changes to minimize confusion with WPForms plugin.
* Fix - Limit number of form filters to 10.
* Tweak - Ask for review only if 20+ entries stored, design changes too.

= 1.4.4 - 30/01/2019 =
* Fix - Check for valid email address on resend form entries
* Add - Recommend plugin fancy fields for wpforms

= 1.4.3 - 23/01/2019 =
* Add - Missing translations for strings.
* Tweak - Deactivation feedback.

= 1.4.2 - 19/01/2019 =
* Feature - Resend email with entries data from within entries section.

= 1.4.1 - 18/01/2019 =
* Fix - Rows and columns mismatch on CSV export
* Fix - Possible authenticated SQL injection
* Fix - Nonce check for every action
* Fix - Move to trash on single entry view
* Tweak - CSS for selected statuses

= 1.4.0 - 08/12/2018 =
* Fix - Order of data entry display
* Fix - Disable geolocation via filter issue.
* Fix - Priority for storing geolocation data.

= 1.3.1 - 30/11/2018 =
* Fix - Form filtering on select dropdown issue
* Remove - Rating text from footer
* Dev - Add option to db update for lower versions

= 1.3.0 - 27/11/2018 =
* Fix - Sorting and ordering of entries
* Tweak - CSS changes

= 1.2.0.1 - 19/11/2018 =
* Fix - Undefined errors call

= 1.2 - 19/11/2018 =
* Add - Filter hook to disable storing geolocation data
* Add - Fallback store services to get geolocation data

= 1.1.2 - 14/10/2018 =
* Add - Feedback link on plugin deactivation

= 1.1.1 - 25/09/2018 =
* Feature - Entries display on frontend

= 1.1.0 - 11/09/2018 =
* Feature - Geolocation data entry save and display

= 1.0.5 - 07/09/2018 =
* Fix - Remove all forms entry display

= 1.0.4 - 26/08/2018 =
* Feature - CSV Export
* Fix - Move CSS to separate file

= 1.0.3 - 23/08/2018 =
* Fix - Load plugin textdomain

= 1.0.2 - 23/08/2018 =
* Fix - Missing files and changes

= 1.0.1 - 22/08/2018 =
* Add - Translation ready texts

= 1.0.0 - 22/08/2018 =
* Initial Release

== Upgrade Notice ==

= 1.4.5 =
This version includes some layout design changes to minimize the confusion between the WPForms and Entries For WPForms add-on. Please adjust.
