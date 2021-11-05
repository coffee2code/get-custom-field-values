=== Get Custom Field Values ===
Contributors: coffee2code
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6ARCFJ9TX3522
Tags: custom fields, widget, widgets, shortcode, meta, extra, data, post, posts, page, pages, coffee2code
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 3.6
Tested up to: 5.8
Stable tag: 4.0

Use widgets, shortcodes, and/or template tags to easily retrieve and display custom field values for posts or pages.


== Description ==

This plugin provides a powerful widget, shortcode (with shortcode builder tool), and template tags for easily retrieving and displaying custom field values for posts or pages.

The power of custom fields gives this plugin the potential to be dozens of plugins all rolled into one.

This plugin allows you to harness the power of custom fields/meta data. Use the "Get Custom Field" widget, the `[custom_field]` shortcode (which has a post editor sidebar widget to help you build the shortcode), or one of six template tags to retrieve and display custom fields. Find a custom field for the current post, a specified post, a recent post, or randomly. And for the custom field values found by the plugin, you may optionally specify text or HTML to appear before and after the results. If nothing is found, then nothing is display (unless you define text to appear when no results are found). If multiple results are found, only the first will be displayed unless you specify a string to be used to join the results together (such as ","), in which case all will be returned. Visit the Examples section to see how this plugin can be cast in dozens of different ways.

There are six template tags provided by this plugin. Here they are, with an explanation of when they are appropriate for use:

* `c2c_get_custom()` : Use this inside "the loop" to retrieve a custom field value for a post
* `c2c_get_current_custom()` : This is only available on the permalink post template (single.php) and page template (page.php). Can be used inside or outside "the loop". Useful for using custom field to define text you want to include on a post or page's header, footer, or sidebar.
* `c2c_get_post_custom()` : Useful when you know the ID of the post whose custom field value you want.
* `c2c_get_random_custom()` : Retrieve the value of a random instance of the specified custom field key, as long as the field is associated with a published posted, non-passworded post (you can modify a setting in the plugin file to search passworded posts as well).
* `c2c_get_random_post_custom()` : Retrieves the value of random custom field(s) from a post when you know the ID of the post you're interested in.
* `c2c_get_recent_custom()` : Retrieves the most recent (according to the associated post's publish date) value of the specified custom field.

You can filter the custom field values that the plugin would display. Add filters for '`the_meta`' to filter custom field data (see the end of the code file for commented out samples you may wish to include). You can also add per-meta filters by hooking '`the_meta_$sanitized_field`'. `$sanitized_field` is a clean version of the value of `$field` where everything but alphanumeric and underscore characters have been removed. So to filter the value of the "Related Posts" custom field, you would need to add a filter for '`the_meta_RelatedPosts`'.

Links: [Plugin Homepage](https://coffee2code.com/wp-plugins/get-custom-field-values/) | [Plugin Directory Page](https://wordpress.org/plugins/get-custom-field-values/) | [GitHub](https://github.com/coffee2code/get-custom-field-values/) | [Author Homepage](https://coffee2code.com)


== Screenshots ==

1. Screenshot of the plugin's widget configuration.
1. Screenshot of the plugin's shortcode builder (not available in the block editor, aka Gutenberg).


== Installation ==

1. Install via the built-in WordPress plugin installer. Or install the plugin code inside the plugins directory for your site (typically `/wp-content/plugins/`).
2. Optional: Add filters for 'the_meta' to filter custom field data (see the end of the plugin file for commented out samples you may wish to include). And/or add per-meta filters by hooking 'the_meta_$field'
3. Activate the plugin through the 'Plugins' admin menu in WordPress
4. Give post(s) a custom field with a value.
5. Optional: Go to the Appearance -> Widgets admin page to create one or more 'Get Custom Field' sidebar widgets for your widget-enabled theme.
6. Optional: Use one of the six template functions provided by this plugin to retrieve the contents of custom fields. You must 'echo' the result if you wish to display the value on your site.
7. Optional: Use the provided shortcode within posts or wherever shortcodes are supported.

== Frequently Asked Questions ==

= I added the template tag to my template and the post has the custom field I'm asking for but I don't see anything about it on the page; what gives? =

Did you `echo` the return value of the function, e.g. `<?php echo c2c_get_custom('mood', 'My mood: '); ?>`

= Can I achieve all the functionality allowed by the six template functions using the widget? =

Except for `c2c_get_custom()` (which is only available inside "the loop"), yes, by carefully setting the appropriate settings for the widget.

= How do I configure the widget to match up with the template functions? =

* `c2c_get_custom()` : not achievable via the widget
* `c2c_get_current_custom()` : set the "Post ID" field to `0`, leave "Pick random value?" unchecked, and set other values as desired.
* `c2c_get_post_custom()` : set the "Post ID" field to the ID of the post you want to reference and set other values as desired.
* `c2c_get_random_custom()` : leave "Post ID" blank, check "Pick random value?", and set other values as desired.
* `c2c_get_random_post_custom()` : set the "Post ID" field to the ID of the post you want to reference, check "Pick random value?", and set other values as desired.
* `c2c_get_recent_custom()` : leave "Post ID" blank, leave "Pick random value?" unchecked, and set other values as desired.

= I don't plan on using the shortcode builder when writing or editing a post or page, so how do I get rid of it? =

If you use the block editor (aka Gutenberg, which is the default editing experience as of WordPress 5.0), then the shortcode builder is not available yet so this situation would be moot for you.

For the classic editor, when on the write or edit admin pages for a page or post, find the "Screen Options" link near the upper right-hand corner of the page. Clicking it slides down a panel of options. In the "Show on screen" section, uncheck the checkbox labeled "Get Custom Field Values - Shortcode". This must be done separately for posts and for pages if you want the shortcode builder disabled for both sections.

Programmatically, see the developer documentation for the `get_custom_field_values/show_metabox` filter for how to completely (or selectively) disable the shortcode builder.

= I don't see the shortcode builder; where is it? =

If you use the block editor (aka Gutenberg, which is the default editing experience as of WordPress 5.0), then the shortcode builder is not available yet.

If you don't have the 'publish_posts' capability (e.g. your role on the site is 'contributor'), then the shortcode builder is not available to you (since this plugin's shortcode is not usable by you).

For the classic editor, the shortcode builder/wizard is available in the admin when writing or editing a page or post. On the edit/create page, it'll be a sidebar widget (in this context, also known as a metabox) labeled "Get Custom Field Values - Shortcode". If you don't see it there (which may be the case since it is hidden by default), find the "Screen Options" link near the upper righthand corner of the page. Clicking it slides down a panel of options. In the "Show on screen" section, check the checkbox labeled "Get Custom Field Values - Shortcode". This must be done separately for posts and for pages if you want the shortcode builder enabled for both sections.

= Can I move the shortcode builder box because it is way down at the bottom of the right sidebar when I create/edit posts? =

Yes, any of the boxes on the page when creating/editing posts can be rearranged by dragging and dropping the box name. At the very top of the shortcode builder box the cursor will turn into a four-way array indicating you can click to drag that box. You can move it under the post content box, or higher up on the right side.

= Why didn't the shortcode get inserted into the editor after I clicked the "Send shortcode to editor" button? =

Sometimes you have to ensure the text editor has focus. Click within the text editor and make sure the cursor is positioned at the location you want the shortcode to be inserted. Then click the button and the shortcode should get inserted there.

= Is this plugin compatible with the new block editor (aka Gutenberg)? =

Yes, except that the shortcode builder (a custom tool to facilitate making use of the plugin's shortcode when creating a post) has not been ported over yet. The template tags, widget, and shortcode itself all function properly.

= Does this plugin include unit tests? =

Yes.


== Developer Documentation ==

Developer documentation can be found in [DEVELOPER-DOCS.md](https://github.com/coffee2code/get-custom-field-values/blob/master/DEVELOPER-DOCS.md). That documentation covers the numerous template tags, hooks, and shortcode provided by the plugin.

As an overview, these are the template tags provided the plugin:

* `c2c_get_custom()`             : Template tag for use inside "the loop" and applies to the currently listed post.
* `c2c_get_current_custom()`     : Template tag for use on permalink (aka single) page templates for posts and pages.
* `c2c_get_post_custom()`        : Template tag for use when you know the ID of the post you're interested in.
* `c2c_get_random_custom()`      : Template tag for use to retrieve a random custom field value.
* `c2c_get_random_post_custom()` : Template tag for use to retrieve random custom field value(s) from a post when you know the ID of the post you're interested in.
* `c2c_get_recent_custom()`      : Template tag for use outside "the loop" and applies for custom fields regardless of post.

These are the hooks provided by the plugin:

* `c2c_get_custom_field_values_shortcode`  : Filter to customize the name of the plugin's shortcode.
* `c2c_get_custom_field_values_post_types` : Filter to customize the post types that should support the shortcode builder metabox.
* `get_custom_field_values/can_author_use_shortcodes` : Filter to customize if post author can make use of the 'custom_field' shortcode.
* `get_custom_field_values/show_metabox`   : Filter to customize if the shortcode builder metabox is shown.


The shortcode provided is `[custom-field]`, which has a number of attributes to customize its behavior and output.


== Changelog ==

= 4.0 (2021-11-04) =
Highlights:

This recommended release prevents users who can't publish posts from using the shortcode in posts (security hardening), adds some new filters, adds DEVELOPER-DOCS.md, notes compatibility through WP 5.8+, and reorganizes and improves unit tests.

Details:

* Change: Prevent users who can't publish posts from using the shortcode in posts. Props Francesco Carlucci.
    * Hardens security to prevent potentail information disclosure or XSS by authors with limited privileges
    * New: Add shortcode class function `can_author_use_shortcodes()`
    * New: Add filter `'get_custom_field_values/can_author_use_shortcodes'`
    * Change: Prevent shortcodes created by users who cannot publish posts from being evaulated
    * Change: Prevent display of shortcode builder metabox to users who cannot publish posts
* New: Add filter `'get_custom_field_values/show_metabox'` to customize if shortcode builder metabox is shown
* New: Add DEVELOPER-DOCS.md and move template tag and shortcode documentation into it
* Change: Note compatibility through WP 5.8+
* Change: Update copyright date (2021)
* Change: Tweak installation instruction
* Unit tests:
    * Change: Split shortcode-related tests out into their own file
    * Change: Split widget-related tests out into their own file
    * New: Add helper functions to facilitate creating users
        * New: Add `create_user()` for creating a user and optionally making them the current user
        * New: Add `unset_current_user()` for unsetting the current user
        * New: Add `tearDown()` to ensure current user gets unset after each test
    * New: Add unit tests for `show_metabox()`
    * Change: Reduce likelihood of particular randomization tests from failing due to reasonable possibility of subsequent randomization choosing the same item
    * Change: Add optional arg `$make_global` (defaulted to false) to `create_post_with_meta()` to facilitate making the created post global
    * Change: Restructure unit test file structure
        * Change: Move `phpunit/bin/` to `tests/bin/`
        * Change: Move `phpunit/bootstrap.php` into `tests/phpunit/`
        * Change: Move tests from `phpunit/tests/` to `tests/phpunit/tests/`
        * Change: In bootstrap, store path to plugin file constant so its value can be used within that file and in test file
        * Change: In bootstrap, check for test installation in more places and exit with error message if not found
        * Change: Remove 'test-' prefix from unit test files
* New: Add a few more possible TODO items

= 3.9.4 (2020-09-11) =
* Change: Restructure unit test file structure
    * New: Create new subdirectory `phpunit/` to house all files related to unit testing
    * Change: Move `bin/` to `phpunit/bin/`
    * Change: Move `tests/bootstrap.php` to `phpunit/`
    * Change: Move `tests/` to `phpunit/tests/`
    * Change: Rename `phpunit.xml` to `phpunit.xml.dist` per best practices
* Change: Note compatibility through WP 5.5+

= 3.9.3 (2020-05-23) =
* New: Add TODO.md and move existing TODO list from top of main plugin file into it
* Change: Update shortcode builder widget to 007:
    * New: Store object instantiated during `register()`
    * Change: Cast return value of `c2c_get_custom_field_values_post_types` filter as an array
    * Change: Sanitize strings used in markup attributes (hardening)
    * Change: Add a missing textdomain for string translation
* Change: Use HTTPS for link to WP SVN repository in bin script for configuring unit tests, and removed commented-out code
* Change: Note compatibility through WP 5.4+
* Change: Update links to coffee2code.com to be HTTPS
* Change: Fix typo in FAQ

_Full changelog is available in [CHANGELOG.md](https://github.com/coffee2code/get-custom-field-values/blob/master/CHANGELOG.md)._


== Upgrade Notice ==

= 4.0 =
Recommended update: Prevented users who can't publish posts from using the shortcode in posts (security hardening), added some new filters, added DEVELOPER-DOCS.md, noted compatibility through WP 5.8+, and reorganized and improved unit tests.

= 3.9.4 =
Trivial update: Restructured unit test file structure and noted compatibility through WP 5.5+.

= 3.9.3 =
Trivial update: Added TODO.md file, tweaked shortcode builder code, updated a few URLs to be HTTPS, and noted compatibility through WP 5.4+.

= 3.9.2 =
Trivial update: modernized unit tests, noted compatibility through WP 5.3+, and updated copyright date (2020)

= 3.9.1 =
Bugfix update (for users of WP earlier than 5.0): Fixed bug preventing post edit page from loading.

= 3.9 =
Recommended update: Fixed minor bug, changed 'this_post' shortcode attribute default to 1 so it can be omitted from most shortcodes, disabled shortcode builder under block editor (it's incompatible), noted compatibility through WP 5.1+, updated copyright date (2019), more.

= 3.8 =
Recommended feature and bugfix update: Added support for percent-substitution tags, properly handled serialized meta values, verified compatibility through WP 4.7+, widget and unit test updates, other minor fixes and updates

= 3.7 =
Minor update: improved support for localization, minor unit test tweaks, verified compatibility through WP 4.4+, and updated copyright date (2016)

= 3.6.1 =
Minor bugfix update: Prevented PHP notice under PHP7+ for widget; added more unit tests; updated widget framework to 010; noted compatibility through WP 4.3+

= 3.6 =
Minor update: added more unit tests; updated widget framework to 009; noted compatibility is now WP 3.6-4.1+; added plugin icon

= 3.5 =
Recommended update: includes the unreleased changes in v3.4; added unit tests; noted compatibility through WP 3.8+

= 3.4 =
Recommended update: added 'id' and 'class' attributes for shortcode, and other shortcode improvements; noted compatibility through WP 3.5+; explicitly stated license

= 3.3.2 =
Recommended bugfix release. Highlights: fixed bug in widget preventing proper display of custom field for current post; noted compatibility through WP 3.3+.

= 3.3.1 =
Critical bugfix release (if using shortcode): fixed fatal shortcode bug

= 3.3 =
Recommended update! added support to c2c_get_random_custom() to return multiple random values; enabled shortcode support for custom fields; noted compatibility through WP 3.2; and more.

= 3.2 =
Recommended update! Highlights: fixed bug with shortcode builder; fixed bug with saving widget; misc non-functionality documentation and formatting tweaks; verified WP 3.0 compatibility; dropped support for versions of WP older than 2.8.
