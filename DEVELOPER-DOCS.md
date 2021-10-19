# Developer Documentation

This plugin provides [template tags](#template-tags), a [shortcode](#shortcode), and [hooks](#hooks).


## Template Tags

The plugin provides six optional template tags for use in your theme templates.

### Functions

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

### Arguments

* `$post_id` _(int)_ :
Required argument (only used in `c2c_get_post_custom()`). The ID of the post from which the custom field should be obtained.

* `$field` _(string)_ :
Required argument. The custom field key of interest.

* `$before` _(string)_ :
Optional argument. The text to display before all the custom field value(s), if any are present (defaults to '').

* `$after` _(string)_ :
Optional argument. The text to display after all the custom field value(s), if any are present (defaults to '')

* `$none` _(string)_ :
Optional argument. The text to display in place of the field value should no field values exist; if defined as '' and no field value exists, then nothing (including no `$before` and `$after`) gets displayed.

* `$between` _(string)_ :
Optional argument. The text to display between multiple occurrences of the custom field; if defined as '', then only the first instance will be used.

* `$before_last` _(string)_ :
Optional argument. The text to display between the next-to-last and last items listed when multiple occurrences of the custom field; `$between` MUST be set to something other than '' for this to take effect.

**Arguments that only apply to `c2c_get_recent_custom()`:**

* `$limit` _(int)_ :
Optional argument. The limit to the number of custom fields to retrieve. (also used by `c2c_get_random_custom` and `c2c_get_random_post_custom()`)

* `$unique` _(boolean_ :
Optional argument. Boolean ('true' or 'false') to indicate if each custom field value in the results should be unique.

* `$order` _(string)_ :
Optional argument. Indicates if the results should be sorted in chronological order ('ASC') (the earliest custom field value listed first), or reverse chronological order ('DESC') (the most recent custom field value listed first).

* `$include_pages` _(boolean)_ :
Optional argument. Boolean ('true' or 'false') to indicate if pages should be included when retrieving recent custom values; default is 'true'.

* `$show_pass_post` _(boolean)_ :
Optional argument. Boolean ('true' or 'false') to indicate if password protected posts should be included when retrieving recent custom values; default is 'false'.

### Examples

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

* ```php
	<?php echo c2c_get_custom('related_offsite_links', 
	   'Here\'s a list of offsite links related to this post:<ol><li><a href="',
	   '">Related</a></li></ol>',
	   '',
	   '">Related</a></li><li><a href="'); ?>
	```

* ```php
	<?php echo c2c_get_custom('more_pictures',
	   'Pictures I\'ve taken today:<br /><div class="more_pictures"><img alt="[photo]" src="',
	   '" /></div>',
	   '',
	   '" /> : <img alt="[photo]" src="'); ?>
	```

* Custom 'more...' link text, by replacing `<?php the_content(); ?>` in index.php with this: `<?php the_content(c2c_get_custom('more', '<span class="morelink">', '</span>', '(more...)')); ?>`

## Shortcode

This plugin provides one shortcode that can be used within the body of a post or page. The shortcode is accompanied by a shortcode builder (see Screenshots) that presents a form for easily creating a shortcode. Here's the documentation for the shortcode and its supported attributes.

The name of the shortcode can be changed via the filter `c2c_get_custom_field_values_shortcode` (though making this customization is only recommended for before your first use of the shortcode, since changing to a new name will cause the shortcodes previously defined using the older name to no longer work).

Note: this plugin's shortcode is only available for use within posts to authors with the 'publish_posts' capability (such as editors and administrators). For authors without that capability (such as contributors), the shortcode builder is not available and any instances of the shortcode in the post are ignored.

### `custom_field`

The only shortcode provided by this plugin is named `custom_field`. It is a self-closing tag, meaning that it is not meant to encapsulate text. Except for 'field', all attributes are optional, though you'll likely need to provide a couple to achieve your desired result.

#### Attributes:

* **field**       : _(string)_ The name of the custom field key whose value you wish to have displayed.
* **id**          : _(string)_ The text to use as the 'id' attribute for a 'span' tag that wraps the output
* **class**       : _(string)_ The text to use as the 'class' attribute for a 'span' tag that wraps the output
* **this_post**   : _(boolean)_ Get the custom field value for the post containing this shortcode? Takes precedence over post_id attribute. Specify `1` (for true) or `0` for false. Default is `1`.
* **post_id**     : (integer)_ ID of post whose custom field's value you want to display. Leave blank to search for the custom field in any post. Use `0` to indicate it should only work on the permalink page for a page/post.
* **random**      : (boolean)_ Pick random value? Specify `1` (for true) or `0` for false. Default is `0`.
* **limit**       : (integer)_ The number of custom field items to list. Only applies if 'post_id' is blank/unset, 'this_post' is 0, and 'random' is blank/unset. Use `0` to indicate no limit. Default is `0`.
* **before**      : _(string)_ Text to display before the custom field.
* **after**       : _(string)_ Text to display after the custom field.
* **none**        : _(string)_ Text to display if no matching custom field is found (or it has no value). Leave this blank if you don't want anything to display when no match is found.
* **between**     : _(string)_ Text to display between custom field items if more than one are being shown. Default is ', '.
* **before_last** : _(string)_ Text to display between the second to last and last custom field items if more than one are being shown.

#### Examples:

* Get list of sizes for the current post
`[custom_field field="size" limit="0" between=", " this_post="1" /]`

* Get random random quote
`[custom_field field="quote" limit="1" random="1" /]`

* Get 3 most recent books read
`[custom_field field="book" limit="3" before="Recently read books: " /]`


## Hooks

The plugin exposes a number of filters for hooking. Code using these filters should ideally be put into a mu-plugin or site-specific plugin (which is beyond the scope of this readme to explain).

### `c2c_get_custom_field_values_shortcode` _(filter)_

The `c2c_get_custom_field_values_shortcode` filter allows you to customize the name of the shortcode.

#### Arguments:

* `$name` _(string)_ :
The name for the shortcode to be handled by this plugin. Default is 'custom_field'. If you opt to change this, you should do so prior to first use of the plugin's shortcode. Once changed, the plugin will no longer recognize any pre-existing shortcodes using the default name.

#### Example:

```php
// Change the Get Custom Field Values shortcode to 'cf' so it is shorter.
add_filter( 'c2c_get_custom_field_values_shortcode', function( $name ) { return 'cf'; } );
```

### `c2c_get_custom_field_values_post_types` _(filter)_

The `c2c_get_custom_field_values_post_types` filter allows you to customize the list of post types for which the shortcode builder (Classic Editor only) will appear. By default, all post types are supported.

#### Arguments:

* `$post_type` _(string[])_ :
The list of post types supported, by name. By default, all public post types are supported.

#### Example:

```php
// Only show Get Custom Field Vavlues shortcode builder for posts.
add_filter( 'c2c_get_custom_field_values_post_types', function( $post_types ) { return array( 'posts' ); } );
```
