=== Get Custom Field Values ===
Contributors: coffee2code
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6ARCFJ9TX3522
Tags: custom fields, widget, widgets, shortcode, meta, extra, data, post, posts, page, pages, coffee2code
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 3.6
Tested up to: 5.1
Stable tag: 3.9

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

Links: [Plugin Homepage](http://coffee2code.com/wp-plugins/get-custom-field-values/) | [Plugin Directory Page](https://wordpress.org/plugins/get-custom-field-values/) | [GitHub](https://github.com/coffee2code/get-custom-field-values/) | [Author Homepage](http://coffee2code.com)


== Screenshots ==

1. Screenshot of the plugin's widget configuration.
1. Screenshot of the plugin's shortcode builder (not available in the block editor, aka Gutenberg).


== Installation ==

1. Install via the built-in WordPress plugin installer. Or download and unzip `get-custom-field-values.zip` inside the plugins directory for your site (typically `wp-content/plugins/`)
2. (optional) Add filters for 'the_meta' to filter custom field data (see the end of the plugin file for commented out samples you may wish to include). And/or add per-meta filters by hooking 'the_meta_$field'
3. Activate the plugin through the 'Plugins' admin menu in WordPress
4. Give post(s) a custom field with a value.
5. (optional) Go to the Appearance -> Widgets admin page to create one or more 'Get Custom Field' sidebar widgets for your widget-enabled theme.
6. (optional) Use one of the six template functions provided by this plugin to retrieve the contents of custom fields. You must 'echo' the result if you wish to display the value on your site.


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

= I don't see the shortcode builder; where is it? =

If you use the block editor (aka Gutenberg, which is the default editing experience as of WordPress 5.0), then the shortcode builder is not available yet.

For the classic editor, the shortcode builder/wizard is available in the admin when writing or editing a page or post. On the edit/create page, it'll be a sidebar widget (in this context, also known as a metabox) labeled "Get User Custom Field Values - Shortcode". If you don't see it there (which may be the case since it is hidden by default), find the "Screen Options" link near the upper righthand corner of the page. Clicking it slides down a panel of options. In the "Show on screen" section, check the checkbox labeled "Get Custom Field Values - Shortcode". This must be done separately for posts and for pages if you want the shortcode builder enabled for both sections.

= Can I move the shortcode builder box because it is way down at the bottom of the right sidebar when I create/edit posts? =

Yes, any of the boxes on the page when creating/editing posts can be rearranged by dragging and dropping the box name. At the very top of the shortcode builder box the cursor will turn into a four-way array indicating you can click to drag that box. You can move it under the post content box, or higher up on the right side.

= Why didn't the shortcode get inserted into the editor after I clicked the "Send shortcode to editor" button? =

Sometimes you have to ensure the text editor has focus. Click within the text editor and make sure the cursor is positioned at the location you want the shortcode to be inserted. Then click the button and the shortcode should get inserted there.

= Is this plugin compatible with the new block editor (aka Gutenberg)? =

Yes, except that the shortcode builder (a custom tool to facilitate making use of the plugin's shortcode when creating a post) has not been ported over yet. The template tags, widget, and shortcode itself all function properly.

= Does this plugin include unit tests? =

Yes.


== Template Tags ==

The plugin provides six optional template tags for use in your theme templates.

= Functions =

* `<?php function c2c_get_custom( $field, $before='', $after='', $none='', $between='', $before_last='' ) ?>`
Template tag for use inside "the loop" and applies to the currently listed post.

* `<?php function c2c_get_current_custom( $field, $before='', $after='', $none='', $between='', $before_last='' ) ?>`

Template tag for use on permalink (aka single) page templates for posts and pages.

* `<?php function c2c_get_post_custom( $post_id, $field, $before='', $after='', $none='', $between='', $before_last='' ) ?>`

Template tag for use when you know the ID of the post you're interested in.

* `<?php function c2c_get_random_custom( $field, $before='', $after='', $none='', $limit=1, $between='', $before_last='' ) ?>`
Template tag for use to retrieve a random custom field value.

* `<?php function c2c_get_random_post_custom( $post_id, $field, $limit=1, $before='', $after='', $none='', $between='', $before_last='' ) ?>`
Template tag for use to retrieve random custom field value(s) from a post when you know the ID of the post you're interested in.

* `<?php function c2c_get_recent_custom( $field, $before='', $after='', $none='', $between=', ', $before_last='', $limit=1, $unique=false, $order='DESC', $include_pages=true, $show_pass_post=false )  ?>`
Template tag for use outside "the loop" and applies for custom fields regardless of post.

= Arguments =

* `$post_id`
Required argument (only used in `c2c_get_post_custom()`). The ID of the post from which the custom field should be obtained.

* `$field`
Required argument. The custom field key of interest.

* `$before`
Optional argument. The text to display before all the custom field value(s), if any are present (defaults to '').

* `$after`
Optional argument. The text to display after all the custom field value(s), if any are present (defaults to '')

* `$none`
Optional argument. The text to display in place of the field value should no field values exist; if defined as '' and no field value exists, then nothing (including no `$before` and `$after`) gets displayed.

* `$between`
Optional argument. The text to display between multiple occurrences of the custom field; if defined as '', then only the first instance will be used.

* `$before_last`
Optional argument. The text to display between the next-to-last and last items listed when multiple occurrences of the custom field; `$between` MUST be set to something other than '' for this to take effect.

Arguments that only apply to `c2c_get_recent_custom()`:

* `$limit`
Optional argument. The limit to the number of custom fields to retrieve. (also used by `c2c_get_random_custom` and `c2c_get_random_post_custom()`)

* `$unique`
Optional argument. Boolean ('true' or 'false') to indicate if each custom field value in the results should be unique.

* `$order`
Optional argument. Indicates if the results should be sorted in chronological order ('ASC') (the earliest custom field value listed first), or reverse chronological order ('DESC') (the most recent custom field value listed first).

* `$include_pages`
Optional argument. Boolean ('true' or 'false') to indicate if pages should be included when retrieving recent custom values; default is 'true'.

* `$show_pass_post`
Optional argument. Boolean ('true' or 'false') to indicate if password protected posts should be included when retrieving recent custom values; default is 'false'.

= Examples =

* `<?php echo c2c_get_custom('mymood'); ?>  // with this simple invocation, you can echo the value of any metadata field`

* `<?php echo c2c_get_custom('mymood', 'Today's moods: ', '', ', '); ?>`

* `<?php echo c2c_get_recent_custom('mymood', 'Most recent mood: '); ?>`

* `<?php echo c2c_get_custom('mymood', '(Current mood: ', ')', ''); ?>`

* `<?php echo c2c_get_custom('mylisten', 'Listening to : ', '', 'No one at the moment.'); ?>`

* `<?php echo c2c_get_custom('myread', 'I\'ve been reading ', ', if you must know.', 'nothing'); ?>`

* `<?php echo c2c_get_custom('todays_link', '<a class="tlink" href="', '" >Today\'s Link</a>'); ?>`

* `<?php echo c2c_get_current_custom('meta_description', '<meta name="description" content="', '" />' ); ?>`

* `<?php echo c2c_get_post_custom($post->ID, 'Price: ', ' (non-refundable)'); ?>`

* `<?php echo c2c_get_random_custom('featured_image', '<img src="/wp-content/images/', '" />'); ?>`

* `<?php echo c2c_get_random_post_custom($post->ID, 'quote', 1, 'Quote: <em>', '</em>'); ?>`

* `<?php echo c2c_get_custom('related_offsite_links', 
	   'Here\'s a list of offsite links related to this post:<ol><li><a href="',
	   '">Related</a></li></ol>',
	   '',
	   '">Related</a></li><li><a href="'); ?>`

* `<?php echo c2c_get_custom('more_pictures',
	   'Pictures I\'ve taken today:<br /><div class="more_pictures"><img alt="[photo]" src="',
	   '" /></div>',
	   '',
	   '" /> : <img alt="[photo]" src="'); ?>`

* Custom 'more...' link text, by replacing `<?php the_content(); ?>` in index.php with this: `<?php the_content(c2c_get_custom('more', '<span class="morelink">', '</span>', '(more...)')); ?>`


== Shortcode ==

This plugin provides one shortcode that can be used within the body of a post or page. The shortcode is accompanied by a shortcode builder (see Screenshots) that presents a form for easily creating a shortcode. However, here's the documentation for the shortcode and its supported attributes.

The name of the shortcode can be changed via the filter 'c2c_get_custom_field_values_shortcode' (though making this customization is only recommended for before your first use of the shortcode, since changing to a new name will cause the shortcodes previously defined using the older name to no longer work).

**custom_field**

The only shortcode provided by this plugin is named `custom_field`. It is a self-closing tag, meaning that it is not meant to encapsulate text. Except for 'field', all attributes are optional, though you'll likely need to provide a couple to achieve your desired result.

Attributes:

* field       : (string) The name of the custom field key whose value you wish to have displayed.
* id          : (string) The text to use as the 'id' attribute for a 'span' tag that wraps the output
* class       : (string) The text to use as the 'class' attribute for a 'span' tag that wraps the output
* this_post   : (boolean) Get the custom field value for the post containing this shortcode? Takes precedence over post_id attribute. Specify `1` (for true) or `0` for false. Default is `1`.
* post_id     : (integer) ID of post whose custom field's value you want to display. Leave blank to search for the custom field in any post. Use `0` to indicate it should only work on the permalink page for a page/post.
* random      : (boolean) Pick random value? Specify `1` (for true) or `0` for false. Default is `0`.
* limit       : (integer) The number of custom field items to list. Only applies if 'post_id' is blank/unset, 'this_post' is 0, and 'random' is blank/unset. Use `0` to indicate no limit. Default is `0`.
* before      : (string) Text to display before the custom field.
* after       : (string) Text to display after the custom field.
* none        : (string) Text to display if no matching custom field is found (or it has no value). Leave this blank if you don't want anything to display when no match is found.
* between     : (string) Text to display between custom field items if more than one are being shown. Default is ', '.
* before_last : (string) Text to display between the second to last and last custom field items if more than one are being shown.

Examples:

* Get list of sizes for the current post
`[custom_field field="size" limit="0" between=", " this_post="1" /]`

* Get random random quote
`[custom_field field="quote" limit="1" random="1" /]`

* Get 3 most recent books read
`[custom_field field="book" limit="3" before="Recently read books: " /]`


== Changelog ==

= 3.9 (2019-03-08) =
* Fix: Default 'this_post' shortcode attribute to 1 instead of 0, since unlike widgets, shortcodes generally appear within the context of a post
* Fix: Call `wpdb::prepare()` with the proper number of arguments depending on context
* Change: Update shortcode builder widget to 005:
    * Don't show shortcode builder metabox within context of block editor
    * Add `show_metabox()`
* Change: Update widget to 012:
    * Directly load textdomain instead of hooking it to already-fired action
* New: Add README.md
* New: Add CHANGELOG.md and move all but most recent changelog entries into it
* Change: Update docs to reflect that shortcode builder is not compatible with block editor yet
* Change: Use different markdown formatting for shortcode name to avoid capitalization when displayed in Plugin Directory
* Change: Add GitHub link to readme
* Change: Unit tests: Minor whitespace tweaks to bootstrap
* Change: Note compatibility through WP 5.1+
* Change: Update copyright date (2019)
* Change: Update License URI to be HTTPS

= 3.8 (2017-03-14) =
* New: Add support for percent-substitution tags
    * Tags can be used in before and/or after text and will be replaced on display with the custom field text
    * Add '%field%' to display custom field name
    * Add '%value%' to display custom field value
    * Add `c2c__gcfv_do_substitutions()` to handle the substitutions
* Fix: Properly handle serialized meta values
* Fix: Properly sanitize field name prior so use as part of a hook name
* Fix: Add missing textdomain for string in shortcode widget
* Change: Update widget to 011:
    * Add `register_widget()` and change to calling it when hooking 'admin_init'
    * Load textdomain
    * Add more substantial unit tests
* Change: Update widget framework:
    * 013:
    * Add `get_config()` as a getter for config array
    * 012:
    * Go back to non-plugin-specific class name of c2c_Widget_012
    * Don't load textdomain
    * Declare class and `load_config()` and `widget_body()` as being abstract
    * Change class variable `$config` from public to protected
    * Discontinue use of `extract()`
    * Apply 'widget_title' filter to widget title
    * Add more inline documentation
    * Minor code reformatting (spacing, bracing, Yoda-ify conditions)
* Change: Update shortcode builder widget to 004:
    * Use `get_config()` to get widget config now that the object variable is protected
    * Add `register()` and change to calling it when hooking 'init'
    * Add more unit tests
* Change: Update unit test bootstrap
    * Default `WP_TESTS_DIR` to `/tmp/wordpress-tests-lib` rather than erroring out if not defined via environment variable
    * Enable more error output for unit tests
* Change: Use officially documented order of arguments for `implode()`
* Change: Rephrase conditions to omit unnecessary use of `empty()`
* Change: Tweak readme.txt (minor content changes, spacing)
* Change: Note compatibility through WP 4.7+
* Change: Update copyright date (2017)
* New: Add LICENSE file

= 3.7 (2016-01-31) =
* Change: Update widget framework to 011:
    * Change class name to c2c_GetCustomFieldValues_Widget_011 to be plugin-specific.
    * Set textdomain using a string instead of a variable.
    * Remove `load_textdomain()` and textdomain class variable.
    * Formatting improvements to inline docs.
* Change: Add support for language packs:
    * Set textdomain using a string instead of a variable.
    * Don't load textdomain from file.
    * Remove .pot file and /lang subdirectory.
    * Remove 'Domain Path' from plugin header.
    * Add 'Text Domain' to plugin header.
* Change: Reformat plugin settings code (spacing).
* Change: Explicitly declare methods in unit tests as public.
* Change: Minor improvements to inline docs and test docs.
* New: Create empty index.php to prevent files from being listed if web server has enabled directory listings.
* Change: Note compatibility through WP 4.4+.
* Change: Update copyright date (2016).

= Full changelog is available in [CHANGELOG.md](CHANGELOG.md). =


== Upgrade Notice ==

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
