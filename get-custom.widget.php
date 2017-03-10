<?php
/**
 * Get Custom Field Values plugin widget code
 *
 * Copyright (c) 2004-2016 by Scott Reilly (aka coffee2code)
 *
 * @package c2c_GetCustomWidget
 * @author  Scott Reilly
 * @version 011
 */

defined( 'ABSPATH' ) or die();

if ( ! class_exists( 'c2c_GetCustomWidget' ) ) :

require_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'c2c-widget.php' );

class c2c_GetCustomWidget extends c2c_GetCustomFieldValues_Widget_011 {

	/**
	 * Returns version of the widget.
	 *
	 * @since 009
	 *
	 * @return string
	 */
	public static function version() {
		return '011';
	}

	/**
	 * Registers the widget.
	 *
	 * @since 011
	 */
	public static function register_widget() {
		register_widget( __CLASS__ );
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct( 'get-custom', __FILE__, array( 'width' => 300 ) );
		add_filter( $this->get_hook( 'excluded_form_options' ), array( $this, 'excluded_form_options' ) );
	}

	/**
	 * Initializes the plugin's configuration and localizable text variables.
	 */
	public function load_config() {
		$this->title       = __( 'Get Custom Field', 'get-custom-field-values' );
		$this->description = __( 'A list of custom field value(s) from posts or pages.', 'get-custom-field-values' );

		$this->config = array(
			// input can be 'checkbox', 'multiselect', 'select', 'short_text', 'text', 'textarea', 'hidden', or 'none'
			// datatype can be 'array' or 'hash'
			// can also specify input_attributes
			'title' => array(
				'input'   => 'text',
				'default' => __( 'Custom Field', 'get-custom-field-values' ),
				'label'   => __( 'Title', 'get-custom-field-values' ),
			),
			'field' => array(
				'input'   => 'text',
				'default' => '',
				'label'   => __( 'Custom field key', 'get-custom-field-values' ),
				'help'    => __( '<strong>*Required.</strong>  The name of the custom field key whose value you wish to have displayed.', 'get-custom-field-values' ),
			),
			'this_post' => array(
				'input'   => 'checkbox',
				'default' => false,
				'label'   => __( 'This post?', 'get-custom-field-values' ),
				'help'    => __( 'The post containing this shortcode. Takes precedence over \'Post ID\'', 'get-custom-field-values' ),
			),
			'post_id' => array(
				'input'   => 'short_text',
				'default' => '',
				'label'   => __( 'Post ID', 'get-custom-field-values' ),
				'help'    => __( 'ID of post whose custom field\'s value you want to display. Leave blank to search for the custom field in any post. Use <code>0</code> to indicate it should only work on the permalink page for a page/post.', 'get-custom-field-values' ),
			),
			'random' =>	array(
				'input'   => 'checkbox',
				'default' => false,
				'label'   => __( 'Pick random value?', 'get-custom-field-values' ),
			),
			'limit' => array(
				'input'   => 'short_text',
				'default' => 0,
				'label'   => __( 'Limit', 'get-custom-field-values' ),
				'help'    => __( 'The number of custom field items to list. Only applies if Post ID is empty and "Pick random value?" is unchecked. Use 0 to indicate no limit.', 'get-custom-field-values' ),
			),
			'before' => array(
				'input'   => 'text',
				'default' => '',
				'label'   => __( 'Before text', 'get-custom-field-values' ),
				'help'    => __( 'Text to display before the custom field.', 'get-custom-field-values' ),
			),
			'after' => array(
				'input'   => 'text',
				'default' => '',
				'label'   => __( 'After text', 'get-custom-field-values' ),
				'help'    => __( 'Text to display after the custom field.', 'get-custom-field-values' ),
			),
			'none' => array(
				'input'   => 'text',
				'default' => '',
				'label'   => __( 'None text', 'get-custom-field-values' ),
				'help'    => __( 'Text to display if no matching custom field is found (or it has no value). Leave this blank if you don\'t want anything to display when no match is found.', 'get-custom-field-values' ),
			),
			'between' => array(
				'input'   => 'text',
				'default' => ', ',
				'label'   => __( 'Between text', 'get-custom-field-values' ),
				'help'    => __( 'Text to display between custom field items if more than one are being shown.', 'get-custom-field-values' ),
			),
			'before_last' => array(
				'input'   => 'text',
				'default' => '',
				'label'   => __( 'Before last text', 'get-custom-field-values' ),
				'help'    => __( 'Text to display between the second to last and last custom field items if more than one are being shown.', 'get-custom-field-values' ),
			),
			'id' => array(
				'input'   => 'text',
				'default' => '',
				'label'   => __( 'HTML id', 'get-custom-field-values' ),
				'help'    => __( 'The \'id\' attribute for the &lt;span&gt; tag to surrounds output.', 'get-custom-field-values' ),
			),
			'class' => array(
				'input'   => 'text',
				'default' => '',
				'label'   => __( 'HTML class', 'get-custom-field-values' ),
				'help'    => __( 'The \'class\' attribute for the &lt;span&gt; tag to surrounds output.', 'get-custom-field-values' ),
			),
		);
	}

	/**
	 * Outputs the body of the widget.
	 *
	 * @param array   $args Widget args.
	 * @param array   $instance Widget instance.
	 * @param array   $settings Widget settings.
	 * @return string The widget body content.
	 */
	public function widget_body( $args, $instance, $settings ) {
		extract( $args );
		extract( $settings );

		// Bail early if no field was specified
		if ( empty( $field ) ) {
			return;
		}

		if ( 0 === $post_id ) {
			$post_id = 'current';
		}

		$body = '';

		// Determine, based on inputs given, which template tag to use.
		if ( ! empty( $post_id ) ) {
			if ( 'current' == $post_id ) {
				$body = c2c_get_current_custom( $field, $before, $after, $none, $between, $before_last );
			} elseif ( $random ) {
				$body = c2c_get_random_post_custom( $post_id, $field, $limit, $before, $after, $none, $between, $before_last );
			} else {
				$body = c2c_get_post_custom( $post_id, $field, $before, $after, $none, $between, $before_last );
			}
		} else {
			if ( $random ) {
				$body = c2c_get_random_custom( $field, $before, $after, $none, $limit, $between, $before_last );
			} else {
				$body = c2c_get_recent_custom( $field, $before, $after, $none, $between, $before_last, $limit );
			}
		}

		// If either 'id' or 'class' attribute was defined, then wrap output in span
		if ( ! empty( $body ) && ! ( empty( $id ) && empty( $class ) ) ) {
			$tag = '<span';

			if ( ! empty( $id ) ) {
				$tag .= ' id="' . esc_attr( $id ) . '"';
			}

			if ( ! empty( $class ) ) {
				$tag .= ' class="' . esc_attr( $class ) . '"';
			}

			$tag .= ">$body</span>";
			$body = $tag;
		}

		return $body;
	}

	/**
	 * Validates widget instance values.
	 *
	 * @param array  $instance Array of widget instance values.
	 * @return array The filtered array of widget instance values.
	 */
	public function validate( $instance ) {
		$instance['field']   = trim( $instance['field'] );
		$instance['limit']   = intval( trim( $instance['limit'] ) );
		$instance['random']  = intval( trim( $instance['random'] ) );
		$instance['post_id'] = trim( $instance['post_id'] );
		if ( '' != $instance['post_id'] ) {
			$instance['post_id'] = intval( $instance['post_id'] );
		}

		return $instance;
	}

	/**
	 * Defines widget form options that shouldn't be shown by default (since they are used for the shortcode widget).
	 *
	 * @param array  $excluded_form_options Array of form options that shouldn't be shown.
	 * @return array The array of form options that shouldn't be shown.
	 */
	public function excluded_form_options( $excluded_form_options ) {
		if ( $excluded_form_options === null ) {
			$excluded_form_options = array( 'this_post' );
		}

		return $excluded_form_options;
	}
} // end class

add_action( 'widgets_init', array( 'c2c_GetCustomWidget', 'register_widget' ) );

endif;
