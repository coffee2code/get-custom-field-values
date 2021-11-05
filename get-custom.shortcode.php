<?php
/**
 * Get Custom Field Values plugin shortcode code.
 *
 * Copyright (c) 2004-2021 by Scott Reilly (aka coffee2code)
 *
 * @package c2c_GetCustomFieldValuesShortcode
 * @author  Scott Reilly
 * @version 007
 */

defined( 'ABSPATH' ) or die();

if ( ! class_exists( 'c2c_GetCustomFieldValuesShortcode' ) && class_exists( 'c2c_GetCustomWidget' ) ) :

class c2c_GetCustomFieldValuesShortcode {
	/**
	 * The instance of this object.
	 *
	 * @access public
	 * @since 007
	 * @var c2c_GetCustomFieldValuesShortcode
	 */
	public static $instance;

	public $name           = 'shortcode_get_custom_field_values';
	public $shortcode      = 'custom_field';
	public $title          = '';
	public $widget_handler = '';
	public $widget_base    = '';

	/**
	 * Returns version of the class.
	 *
	 * @since 003
	 *
	 * @return string
	 */
	public static function version() {
		return '007';
	}

	/**
	 * Registers the shortcode.
	 *
	 * @since 004
	 */
	public static function register() {
		self::$instance = new self( $GLOBALS['wp_widget_factory']->widgets['c2c_GetCustomWidget'] );
	}

	/**
	 * Constructor.
	 */
	public function __construct( $widget_handler ) {
		$this->title          = __( 'Get Custom Field Values - Shortcode', 'get-custom-field-values' );
		$this->widget_handler = $widget_handler;
		$this->widget_base    = 'widget-' . $this->widget_handler->id_base;
		$this->shortcode      = apply_filters( 'c2c_get_custom_field_values_shortcode', $this->shortcode );

		add_shortcode( $this->shortcode,         array( $this, 'shortcode' ) );
		add_action( 'load-post.php',             array( $this, 'register_post_page_hooks' ) );
		add_action( 'load-post-new.php',         array( $this, 'register_post_page_hooks' ) );
		add_filter( 'default_hidden_meta_boxes', array( $this, 'default_hidden_meta_boxes' ), 10, 2 );
	}

	/**
	 * Filters/actions to hook on the admin post.php page.
	 *
	 * @since 3.4
	 */
	public function register_post_page_hooks() {
		add_action( 'admin_print_footer_scripts', array( $this, 'admin_js' ) );
		add_action( 'do_meta_boxes',              array( $this, 'do_meta_box' ), 10, 3 );
	}

	/**
	 * Determines if post author can use shortcodes.
	 *
	 * @since 4.0
	 *
	 * @param null|int|WP_User $user The user ID, object, or null to refer to
	 *                               current post's author. Default null.
	 * @param null|int|WP_Post $post The post ID, object, or null to refer to
	 *                               current post. Default null.
	 * @return bool True if post author can use shortcode, else false.
	 */
	public function can_author_use_shortcodes( $user = null, $post = null ) {
		// Default to allowing shortcode usage as shortcode may appear in non-post contexts.
		// It is assumed those other contexts are trusted.
		$can = true;

		// If post not provided, use global post.
		$post = get_post( $post );

		// If user provided, get its object. Else, assume current post's author.
		if ( $user ) {
			if ( is_int( $user ) ) {
				$user = get_userdata( (int) $user );
			}
		} else {
			if ( $post ) {
				$user = get_userdata( $post->post_author );
			}
		}

		// User must have 'publish_posts' capability, which requires a user to
		// be known.
		if ( $user instanceof WP_User ) {
			$can = user_can( $user->ID, 'publish_posts' );
		}

		/**
		 * Filters if a given author (if applicable given the shortcode context),
		 * can use the 'custom_field' shortcode, or to have their use of it
		 * evaluated.
		 *
		 * Note: In contexts where no author is relevant (such as a shortcode
		 * appearing in a sidebar widget), then use of the shortcode is
		 * assumed to be allowed and trusted.
		 *
		 * @since 4.0
		 *
		 * @param bool          $can  Is shortcode use permitted?
		 * @param WP_User|false $user User object, or false if no user in context.
		 * @param WP_Post|false $post Post object, or false if no post in context.
		 */
		return (bool) apply_filters( 'get_custom_field_values/can_author_use_shortcodes', $can, $user, $post );
	}

	/**
	 * Determines if the metabox should be shown.
	 *
	 * The metabox should only be shown in the classic editor.
	 *
	 * @since 005
	 * @since 4.0 Prevent users who cannot publish posts from seeing the metabox.
	 *
	 * @return bool True if the metabox can be shown, false otherwise.
	 */
	public function show_metabox() {
		$current_screen = get_current_screen();
		$show = true;

		// Show metabox if not the block editor.
		if ( $show ) {
			$show = ! method_exists( $current_screen, 'is_block_editor' ) || ! $current_screen->is_block_editor();
		}

		// Don't show if author cannot publish post.
		if ( $show ) {
			$show = (bool) apply_filters( 'get_custom_field_values/show_metabox', $this->can_author_use_shortcodes() );
		}

		return $show;
	}

	/**
	 * Register meta box
	 *
	 * By default, the shortcode builder is present for all post types. Filter
	 * 'c2c_get_custom_field_values_post_types' to limit its use.
	 *
	 * @since 3.4
	 *
	 * @param string  $post_type The post type
	 * @param string  $type      The mode for the meta box (normal, advanced, or side)
	 * @param WP_Post $post      The post
	 */
	public function do_meta_box( $post_type, $type, $post ) {
		if ( ! $this->show_metabox() ) {
			return;
		}

		$post_types = (array) apply_filters( 'c2c_get_custom_field_values_post_types', get_post_types() );
		if ( ! in_array( $post_type, $post_types ) ) {
			return;
		}

		add_meta_box( $this->name, $this->title, array( $this, 'form' ), $post_type, 'side', 'core' );
	}

	/**
	 * Hides the shortcode wizard by default.
	 *
	 * @since 3.5
	 *
	 * @param array     $hidden List of all hidden metaboxes
	 * @param WP_Screen $screen Screen object.
	 */
	public function default_hidden_meta_boxes( $hidden, $screen ) {
		if ( ! in_array( $this->name, $hidden ) ) {
			$hidden[] = $this->name;
		}

		return $hidden;
	}

	/**
	 * Shortcode handler.
	 *
	 * @since 4.0 Prevent evaluation of shortcodes in posts author by users who cannot publish posts.
	 *
	 * @param array  $atts    The shortcode attributes
	 * @param string $content The text wrapped by the shortcode's opening and closing tags.
	 * @return string
	 */
	public function shortcode( $atts, $content = null ) {
		$defaults = array();

		if ( ! $this->can_author_use_shortcodes() ) {
			return '';
		}

		foreach ( $this->widget_handler->get_config() as $opt => $values ) {
			// Unlike widgets, shortcodes generally exist in the context of a post, so
			// override widget default.
			if ( 'this_post' === $opt ) {
				$values['default'] = true;
			}

			$defaults[ $opt ] = isset( $values['default'] ) ? $values['default'] : '';
		}

		$atts2 = shortcode_atts( $defaults, $atts );

		foreach ( array_keys( $this->widget_handler->get_config() ) as $key ) {
			$$key = $atts2[ $key ];
		}

		$ret = '';

		if ( $post_id ) {
			if ( 'current' == $post_id ) {
				$ret = c2c_get_current_custom( $field, $before, $after, $none, $between, $before_last );
			} elseif ( $random ) {
				$ret = c2c_get_random_post_custom( $post_id, $field, $limit, $before, $after, $none, $between, $before_last );
			} else {
				$ret = c2c_get_post_custom( $post_id, $field, $before, $after, $none, $between, $before_last );
			}
		} else {
			if ( $this_post ) {
				$ret = c2c_get_custom( $field, $before, $after, $none, $between, $before_last );
			} elseif ( $random ) {
				$ret = c2c_get_random_custom( $field, $before, $after, $none );
			} else {
				$ret = c2c_get_recent_custom( $field, $before, $after, $none, $between, $before_last, $limit );
			}
		}

		// If either 'id' or 'class' attribute was defined, then wrap output in span
		if ( $ret && ( $id || $class ) ) {
			$tag = '<span';
			if ( $id ) {
				$tag .= ' id="' . esc_attr( $id ) . '"';
			}
			if ( $class ) {
				$tag .= ' class="' . esc_attr( $class ) . '"';
			}
			$tag .= ">$ret</span>";
			$ret = $tag;
		}

		return $ret;
	}

	/**
	 * Outputs the JS for the shortcode wizard.
	 */
	public function admin_js() {
		if ( ! $this->show_metabox() ) {
			return;
		}

		echo <<<JS
		<script type="text/javascript">
			jQuery.noConflict();
			var {$this->name} = function () {}

			{$this->name}.prototype = {
				options           : {},
				generateShortCode : function() {
					var content = this['options']['content'];
					delete this['options']['content'];

					var attrs = '';
					jQuery.each(this['options'], function(name, value){
						if (value != '') {
							attrs += ' ' + name + '="' + value + '"';
						}
					});
					return '[{$this->shortcode}' + attrs + ' /]';
				},
				sendToEditor      : function(f) {
					var collection = jQuery(f).find("input[id^={$this->widget_base}]:not(input:checkbox), \
										input[id^={$this->widget_base}]:checkbox:checked, \
										textarea[id^={$this->widget_base}]");
					var \$this = this;
					collection.each(function () {
						var name = this.name.substring(this.name.lastIndexOf('[')+1, this.name.length-1);
						if (\$this['options'][name] == undefined)
							\$this['options'][name] = this.value;
						else
							\$this['options'][name] += ', '+this.value;
					});

					// Delete between and before_last values if a limit of 1 was specified (since there will be at most 1)
					if ( \$this['options']['limit'] == '1' ) {
						delete \$this['options']['between'];
						delete \$this['options']['before_last'];
					}

					send_to_editor(this.generateShortCode());
					/* Delete data after generating shortcode so that the form can be used to generate another shortcode */
					collection.each(function () {
						var name = this.name.substring(this.name.lastIndexOf('[')+1, this.name.length-1);
						delete \$this['options'][name];
					});
					return false;
				}
			}

			var admin_{$this->name} = new {$this->name}();
		</script>

JS;
	}

	/**
	 * Outputs shortcode wizard form.
	 */
	public function form() {
		$this->widget_handler->form( array(), array( 'title' ) );
		printf(
			'<p class="submit"><input type="button" class="button-primary" onclick="return admin_%s.sendToEditor(this.form);" value="%s" /></p>' . "\n",
			esc_attr( $this->name ),
			esc_attr__( 'Send shortcode to editor', 'get-custom-field-values' )
		);
	}

} // end class c2c_GetCustomFieldValuesShortcode

add_action( 'init', array( 'c2c_GetCustomFieldValuesShortcode', 'register' ), 11 );

endif; // end if !class_exists()
