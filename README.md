# Get Custom Field Values

A plugin for WordPress that provides widgets, shortcodes, and template tags to easily retrieve and display custom field values for posts or pages.


## Installation

1. Install via the built-in WordPress plugin installer. Or install the plugin code inside the plugins directory for your site (typically `/wp-content/plugins/`).
2. Activate the plugin through the 'Plugins' admin menu in WordPress
3. Optional: Add filters for `the_meta` to filter custom field data (see the end of the plugin file for commented out samples you may wish to include). And/or add per-meta filters by hooking `the_meta_$field`
4. Give post(s) a custom field with a value.
5. Optional: Go to the Appearance -> Widgets admin page to create one or more 'Get Custom Field' sidebar widgets for your widget-enabled theme.
6. Optional: Use one of the six template functions provided by this plugin to retrieve the contents of custom fields. You must 'echo' the result if you wish to display the value on your site.


## Additional Documentation

See [readme.txt](https://github.com/coffee2code/get-custom-field-values/blob/master/readme.txt) for additional usage information. See [CHANGELOG.md](CHANGELOG.md) for the list of changes for each release.


## Support

Commercial support and custom development are not presently available. You can raise an [issue](https://github.com/coffee2code/get-custom-field-values/issues) on GitHub or post in the [plugin's support forum on WordPress.org](https://wordpress.org/support/plugin/get-custom-field-values/).

If the plugin has been of benefit to you, how about [submitting a review](https://wordpress.org/support/plugin/get-custom-field-values/reviews/) for it in the WordPress Plugin Directory or considering a [donation](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6ARCFJ9TX3522)?


## License

This plugin is free software; you can redistribute it and/or modify it under the terms of the [GNU General Public License](https://www.gnu.org/licenses/gpl-2.0.html) as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.