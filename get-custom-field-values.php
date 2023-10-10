<?php
/**
 * Plugin Name: Get Custom Field Values
 * Version:     4.1
 * Plugin URI:  https://coffee2code.com/wp-plugins/get-custom-field-values/
 * Author:      Scott Reilly
 * Author URI:  https://coffee2code.com/
 * Text Domain: get-custom-field-values
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Description: Use widgets, shortcodes, and/or template tags to easily retrieve and display custom field values for posts or pages.
 *
 * Compatible with WordPress 3.6+ through 6.3+.
 *
 * =>> Read the accompanying readme.txt file for instructions and documentation.
 * =>> Also, visit the plugin's homepage for additional information and updates.
 * =>> Or visit: https://wordpress.org/plugins/get-custom-field-values/
 *
 * @package Get_Custom_Field_Values
 * @author  Scott Reilly
 * @version 4.1
 */

/*
	Copyright (c) 2004-2023 by Scott Reilly (aka coffee2code)

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

defined( 'ABSPATH' ) or die();

require_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'get-custom.widget.php' );
require_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'get-custom.shortcode.php' );

if ( ! function_exists( 'c2c_get_custom' ) ) :
/**
 * Template tag for use inside "the loop" to display custom field value(s) for the current post.
 *
 * @param string  $field       The name/key of the custom field.
 * @param string  $before      Optional. The text to display before all the custom field value(s), if any are present. Default ''.
 * @param string  $after       Optional. The text to display after all the custom field value(s), if any are present. Default ''.
 * @param string  $none        Optional. The text to display in place of the field value should no field values exist; if defined
 *                             as '' and no field value exists, then nothing (including no `$before` and `$after`) gets displayed.
 * @param string  $between     Optional. The text to display between multiple occurrences of the custom field; if defined as '',
 *                             then only the first instance will be used.
 * @param string  $before_last Optional. The text to display between the next-to-last and last items listed when multiple occurrences
 *                             of the custom field; `$between` MUST be set to something other than '' for this to take effect.
 * @return string The formatted string.
 */
function c2c_get_custom( $field, $before='', $after='', $none='', $between='', $before_last='' ) {
	return c2c__format_custom( $field, (array) get_post_custom_values( $field ), $before, $after, $none, $between, $before_last );
}
endif;


if ( ! function_exists( 'c2c_get_current_custom' ) ) :
/**
 * Template tag for use on permalink (aka single) page templates for posts and pages.
 *
 * @param string  $field       The name/key of the custom field.
 * @param string  $before      Optional. The text to display before all the custom field value(s), if any are present. Default ''.
 * @param string  $after       Optional. The text to display after all the custom field value(s), if any are present. Default ''.
 * @param string  $none        Optional. The text to display in place of the field value should no field values exist; if defined
 *                             as '' and no field value exists, then nothing (including no `$before` and `$after`) gets displayed.
 * @param string  $between     Optional. The text to display between multiple occurrences of the custom field; if defined as '',
 *                             then only the first instance will be used.
 * @param string  $before_last Optional. The text to display between the next-to-last and last items listed when multiple occurrences
 *                             of the custom field; `$between` MUST be set to something other than '' for this to take effect.
 * @return string The formatted string.
 */
function c2c_get_current_custom( $field, $before='', $after='', $none='', $between='', $before_last='' ) {
	if ( ! ( is_single() || is_page() ) ) {
		return;
	}

	global $wp_query;
	$post_obj = $wp_query->get_queried_object();
	$post_id  = $post_obj->ID;

	return c2c__format_custom( $field, (array) get_post_custom_values( $field, $post_id ), $before, $after, $none, $between, $before_last );
}
endif;


if ( ! function_exists( 'c2c_get_post_custom' ) ) :
/**
 * Template tag for use when you know the ID of the post you're interested in.
 *
 * @param int     $post_id     Post ID.
 * @param string  $field       The name/key of the custom field.
 * @param string  $before      Optional. The text to display before all the custom field value(s), if any are present. Default ''.
 * @param string  $after       Optional. The text to display after all the custom field value(s), if any are present. Default ''.
 * @param string  $none        Optional. The text to display in place of the field value should no field values exist; if defined
 *                             as '' and no field value exists, then nothing (including no `$before` and `$after`) gets displayed.
 * @param string  $between     Optional. The text to display between multiple occurrences of the custom field; if defined as '',
 *                             then only the first instance will be used.
 * @param string  $before_last Optional. The text to display between the next-to-last and last items listed when multiple occurrences
 *                             of the custom field; `$between` MUST be set to something other than '' for this to take effect.
 * @return string The formatted string.
 */
function c2c_get_post_custom( $post_id, $field, $before='', $after='', $none='', $between='', $before_last='' ) {
	return c2c__format_custom( $field, (array) get_post_custom_values( $field, $post_id ), $before, $after, $none, $between, $before_last );
}
endif;


if ( ! function_exists( 'c2c_get_random_custom' ) ) :
/**
 * Template tag for use to retrieve a random custom field value.
 *
 * @param string  $field       The name/key of the custom field.
 * @param string  $field       The name/key of the custom field.
 * @param string  $before      Optional. The text to display before all the custom field value(s), if any are present. Default ''.
 * @param string  $after       Optional. The text to display after all the custom field value(s), if any are present. Default ''.
 * @param string  $none        Optional. The text to display in place of the field value should no field values exist; if defined
 *                             as '' and no field value exists, then nothing (including no `$before` and `$after`) gets displayed.
 * @param int     $limit       Optional. The limit to the number of custom fields to retrieve. Use 0 to indicate no limit. Default 1.
 * @param string  $between     Optional. The text to display between multiple occurrences of the custom field; if defined as '',
 *                             then only the first instance will be used.
 * @param string  $before_last Optional. The text to display between the next-to-last and last items listed when multiple occurrences
 *                             of the custom field; `$between` MUST be set to something other than '' for this to take effect.
 * @return string The formatted string.
 */
function c2c_get_random_custom( $field, $before='', $after='', $none='', $limit=1, $between=', ', $before_last='' ) {
	global $wpdb;
	$search_passworded_posts = false;
	
	$sql = "SELECT postmeta.meta_value FROM $wpdb->postmeta as postmeta 
				LEFT JOIN $wpdb->posts AS posts ON (posts.ID = postmeta.post_id)
				WHERE postmeta.meta_key = %s AND postmeta.meta_value != ''
				AND posts.post_status = 'publish' ";

	if ( $search_passworded_posts ) {
		$sql .= "AND posts.post_password = '' ";
	}

	$sql .= 'ORDER BY rand() LIMIT %d';

	// If one object was requested, return the single object, else return an array of objects
	if ( $limit > 1 ) {
		$value = $wpdb->get_col( $wpdb->prepare( $sql, $field, $limit ) );
	} else {
		$value = (array) $wpdb->get_var( $wpdb->prepare( $sql, $field, $limit ) );
	}

	return c2c__format_custom( $field, $value, $before, $after, $none, $between, $before_last );
}
endif;


if ( ! function_exists( 'c2c_get_random_post_custom' ) ) :
/**
 * Template tag for use to retrieve random custom field value(s) from a post when you know the ID of the post you're interested in.
 *
 * @param int     $post_id     Post ID.
 * @param string  $field       The name/key of the custom field.
 * @param int     $limit       Optional. The limit to the number of custom fields to retrieve. Use 0 to indicate no limit. Default 1.
 * @param string  $before      Optional. The text to display before all the custom field value(s), if any are present. Default ''.
 * @param string  $after       Optional. The text to display after all the custom field value(s), if any are present. Default ''.
 * @param string  $none        Optional. The text to display in place of the field value should no field values exist; if defined
 *                             as '' and no field value exists, then nothing (including no `$before` and `$after`) gets displayed.
 * @param string  $between     Optional. The text to display between multiple occurrences of the custom field; if defined as '',
 *                             then only the first instance will be used.
 * @param string  $before_last Optional. The text to display between the next-to-last and last items listed when multiple occurrences
 *                             of the custom field; `$between` MUST be set to something other than '' for this to take effect.
 * @return string The formatted string.
 */
function c2c_get_random_post_custom( $post_id, $field, $limit=1, $before='', $after='', $none='', $between='', $before_last='' ) {
	$cfields = (array) get_post_custom_values( $field, $post_id );
	shuffle( $cfields );
	$limit = absint( $limit );

	if ( $limit != 0 && count( $cfields ) > $limit ) {
		$cfields = array_slice( $cfields, 0, $limit );
	}

	return c2c__format_custom( $field, $cfields, $before, $after, $none, $between, $before_last );
}
endif;


if ( ! function_exists( 'c2c_get_recent_custom' ) ) :
/**
 * Template tag for use outside "the loop" and applies for custom fields regardless of post
 *
 * @param string  $field          The name/key of the custom field
 * @param string  $before         Optional. The text to display before all the custom field value(s), if any are present. Default ''.
 * @param string  $after          Optional. The text to display after all the custom field value(s), if any are present. Default ''.
 * @param string  $none           Optional. The text to display in place of the field value should no field values exist; if defined
 *                                as '' and no field value exists, then nothing (including no `$before` and `$after`) gets displayed.
 * @param string  $between        Optional. The text to display between multiple occurrences of the custom field; if defined as '',
 *                                then only the first instance will be used.
 * @param string  $before_last    Optional. The text to display between the next-to-last and last items listed when multiple occurrences
 *                                of the custom field; `$between` MUST be set to something other than '' for this to take effect.
 * @param int     $limit          Optional. The limit to the number of custom fields to retrieve. Use 0 to indicate no limit. Default 1.
 * @param bool    $unique         Optional. Should each custom field value in the results be unique? Default false.
 * @param string  $order          Optional. Indicates if the results should be sorted in chronological order ('ASC') (the earliest custom field value listed first), or reverse chronological order ('DESC') (the most recent custom field value listed first). Default 'DESC'.
 * @param bool    $include_pages  Optional. Should pages be included when retrieving recent custom values? Default true.
 * @param bool    $show_pass_post Optional. Should password protected posts be included? Default false.
 * @return string The formatted string.
 */
function c2c_get_recent_custom( $field, $before='', $after='', $none='', $between=', ', $before_last='', $limit=1, $unique=false, $order='DESC', $include_pages=true, $show_pass_post=false ) {
	global $wpdb;

	$limit = absint( $limit );

	if ( ! $between ) {
		$limit = 1;
	}

	if ( $order != 'ASC' ) {
		$order = 'DESC';
	}

	$sql = "SELECT ";

	if ( $unique ) {
		$sql .= "DISTINCT ";
	}

	$sql .= "meta_value FROM $wpdb->posts AS posts, $wpdb->postmeta AS postmeta ";
	$sql .= "WHERE posts.ID = postmeta.post_id AND postmeta.meta_key = %s ";
	$sql .= "AND posts.post_status = 'publish' AND ( posts.post_type = 'post' ";

	if ( $include_pages ) {
		$sql .= "OR posts.post_type = 'page' ";
	}

	$sql .= ') ';

	if ( ! $show_pass_post ) {
		$sql .= "AND posts.post_password = '' ";
	}

	$sql .= "AND postmeta.meta_value != '' ";
	$sql .= "ORDER BY posts.post_date $order"; // Value is restricted to 2 valid values above

	if ( $limit > 0 ) {
		$sql .= ' LIMIT %d';
	}

	$values  = array();
	if ( $limit > 0 ) {
		$results = $wpdb->get_results( $wpdb->prepare( $sql, $field, $limit ) );
	} else {
		$results = $wpdb->get_results( $wpdb->prepare( $sql, $field ) );
	}

	if ( $results ) {
		foreach ( $results as $result ) { $values[] = $result->meta_value; };
	}

	return c2c__format_custom( $field, $values, $before, $after, $none, $between, $before_last );
}
endif;


if ( ! function_exists( 'c2c__format_custom' ) ) :
/**
 * Helper function
 *
 * @param string  $field       The name/key of the custom field
 * @param array   $meta_values Array of custom field values
 * @param string  $before      Optional. The text to display before all the custom field value(s), if any are present. Default ''.
 * @param string  $after       Optional. The text to display after all the custom field value(s), if any are present. Default ''.
 * @param string  $none        Optional. The text to display in place of the field value should no field values exist; if defined
 *                             as '' and no field value exists, then nothing (including no `$before` and `$after`) gets displayed.
 * @param string  $between     Optional. The text to display between multiple occurrences of the custom field; if defined as '',
 *                             then only the first instance will be used.
 * @param string  $before_last Optional. The text to display between the next-to-last and last items listed when multiple occurrences
 *                             of the custom field; `$between` MUST be set to something other than '' for this to take effect.
 * @return string The formatted string.
 */
function c2c__format_custom( $field, $meta_values, $before='', $after='', $none='', $between='', $before_last='' ) {
	$values = array();

	if ( ! $between ) {
		$meta_values = array_slice( $meta_values, 0, 1 );
	}

	if ( $meta_values ) {
		$sanitized_field = sanitize_key( $field );

		foreach ( $meta_values as $metas ) {
			$metas = maybe_unserialize( $metas );

			foreach ( (array) $metas as $meta ) {
				$meta     = apply_filters( "the_meta_$sanitized_field", $meta );
				$values[] = apply_filters( 'the_meta', $meta );
			}
		}
	}

	if ( ! $values ) {
		$value = '';
	} else {
		$values = array_map( 'trim', $values );

		if ( ! $before_last ) {
			$value = implode( $between, $values );
		} else {
			switch ( $size = sizeof( $values ) ) {
				case 1:
					$value = $values[0];
					break;
				case 2:
					$value = $values[0] . $before_last . $values[1];
					break;
				default:
					$value = implode( $between, array_slice( $values, 0, $size-1 ) ) . $before_last . $values[ $size-1 ];
			}
		}
	}

	if ( ! $value ) {
		if ( ! $none ) {
			return;
		}
		$value = $none;
	}

	return c2c__gcfv_do_substitutions( $before, $field, $value ) . $value . c2c__gcfv_do_substitutions( $after, $field, $value );
}
endif;

add_filter( 'the_meta', 'do_shortcode' );


/**
 * Performs substitutions of percent-substitution tags within text.
 *
 * Recognized substitution tags:
 * - %field% : The custom field name.
 * - %value% : The value of the custom field for a post.
 *
 * @since 3.8
 * @access private
 *
 * @param string $text  The text to search for substitutions.
 * @param string $field The custom field name.
 * @param string $value The value for the custom field.
 * @return string
 */
function c2c__gcfv_do_substitutions( $text, $field, $value ) {
	if ( false === strpos( $text, '%' ) ) {
		return $text;
	}

	$replacements = array(
		'%field%' => $field,
		'%value%' => $value,
	);

	foreach ( $replacements as $key => $value ) {
		$text = str_replace( $key, $value, $text );
	}

	return $text;
}

// Some filters you may wish to perform: (these are filters typically done to 'the_content' (post content))
//add_filter('the_meta', 'convert_chars');
//add_filter('the_meta', 'wptexturize');

// Other optional filters (you would need to obtain and activate these plugins before trying to use these)
//add_filter('the_meta', 'c2c_hyperlink_urls', 9);
//add_filter('the_meta', 'text_replace', 2);
//add_filter('the_meta', 'textile', 6);
