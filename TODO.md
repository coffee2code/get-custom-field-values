# TODO

The following list comprises ideas, suggestions, and known issues, all of which are in consideration for possible implementation in future releases.

***This is not a roadmap or a task list.*** Just because something is listed does not necessarily mean it will ever actually get implemented. Some might be bad ideas. Some might be impractical. Some might either not benefit enough users to justify the effort or might negatively impact too many existing users. Or I may not have the time to devote to the task.

* Add `$order` arg to `c2c_get_custom()`, `c2c_get_current_custom()`
* Create hooks to allow disabling shortcode, shortcode builder, and widget support
* Use `WP_Query` when possible
* Facilitate conditional output, maybe via `c2c_get_custom_if()` where text is only output if post has the custom field AND it equals a specified value (or one of an array of possible values), e.g. `echo c2c_get_custom_if( 'size', array( 'XL', 'XXL' ), 'Sorry, this size is out of stock.' );`
* Introduce a 'format' shortcode attribute and template tag argument. Defines the output format for each matching custom field, e.g. `c2c_get_custom(..., $format = 'Size %key% has %value%' in stock.')`
* Support specifying `$field` as array or comma-separated list of custom fields
* Create args array alternative template tag: `c2c_custom_field( $field, $args = array() )` so features can be added and multiple arguments don't have to be explicitly provided. Perhaps transition `c2c_get_custom()` in plugin v4.0 and detect args:

   ```
    function c2c_get_custom( $field, $args = array() ) {
        // Support legacy parameters.
        if ( ! empty( $args ) && ! is_array( $args ) ) {
            return c2c_old_get_custom( $field, ... ); // Or: $args = c2c_get_custom_args_into_array( ... );
        }
        // Continue with existing code, but referencing $args for parameter values.
    }
   ```
* Support retrieving custom fields for one or more specific post_types, e.g. `c2c_get_custom( 'colors', array( 'post_type' => array( 'pants', 'shorts' ) ) )`
* Support name filters to run against found custom fields, e.g. `c2c_get_custom( 'colors', array( 'filters' => array( 'strtoupper', 'make_clickable' ) ) )`
* Since it is shifting to args array, might as well support 'echo'
* Move shortcode wizard JS into file so it can be enqueued
* Handle serialized custom field values
* Gutenberg: Adapt shortcode widget to block editor. Wizard should permit adding custom fields inline or as blocks.
* Discontinue use of jQuery in favor of vanilla JS
* Add CLI support for more post meta commands. (Or as new plugin, Post Meta CLI?)
* Disable use of shortcode in comments, even if shortcodes in comments are enabled (which they aren't, but if a site went out of its way to allow shortcodes in comments, they may not anticipate the potential for information disclosure by allowing use of this particular shortcode)
    * Require separate filter to enable
* Add function invocation action hooks
* Shortcode: Allow authors who cannot publish posts to use shortcode to reference post meta in the post the shortcode appears in
    * Ideally, also any of their other posts
    * Also ideally, post meta publicly exposed and available via REST API
* Shortcode: Add filter `'get_custom_field_values/allow_shortcode_usage'` in `shortcode()`, that passes result of `can_author_use_shortcodes()` as first arg, but also all info about shortcode usage. Allows for fine-grained determination if a specific shortcode use is allowed/disallowed.
* Abandon separate versioning of shortcode and widget classes
    * Update `@since` values for each class to corresponding plugin versions
* Document the `the_meta*` filters (inline and in dev docs)
* Deprecate and rename the `the_meta*` filters? (to `get_custom_field_values/the_meta`)
* Wrap all non-template tag functions into a class

Feel free to make your own suggestions or champion for something already on the list (via the [plugin's support forum on WordPress.org](https://wordpress.org/support/plugin/get-custom-field-values/) or on [GitHub](https://github.com/coffee2code/get-custom-field-values/) as an issue or PR).
