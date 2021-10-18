<?php

defined( 'ABSPATH' ) or die();

class Get_Custom_Field_Values_Shortcode_Test extends WP_UnitTestCase {

	public function tearDown() {
		parent::tearDown();
		$this->unset_current_user();
		$GLOBALS['post'] = null;
	}

	//
	//
	// HELPER FUNCTIONS
	//
	//


	private function create_post_with_meta( $metas = array(), $post_data = array(), $make_global = false ) {
		$post_id = $this->factory->post->create( $post_data );

		if ( ! $metas ) {
			$metas = $this->get_sample_metas();
		}

		foreach ( $metas as $key => $val ) {
			if ( is_array( $val ) ) {
				foreach ( $val as $v ) {
					add_post_meta( $post_id, $key, $v );
				}
			} else {
				add_post_meta( $post_id, $key, $val );
			}
		}

		if ( $make_global ) {
			global $post;
			$post = get_post( $post_id );
		}

		return $post_id;
	}

	private function get_sample_metas() {
		return array(
			'mood'  => 'happy',
			'child' => array( 'adam', 'bob', 'cerise', 'diane' ),
			'color' => array( 'blue', 'white' ),
			'tshirt size' => 'M',
			'location' => 'Denver, CO',
			'Website' => 'example.com',
			'secret'  => 'abc',
		);
	}

	private function create_user( $set_as_current = false, $user_args = array() ) {
		$user_id = $this->factory->user->create( $user_args );
		if ( $set_as_current ) {
			wp_set_current_user( $user_id );
		}
		return $user_id;
	}

	// helper function, unsets current user globally. Taken from post.php test.
	private function unset_current_user() {
		global $current_user, $user_ID;

		$current_user = $user_ID = null;
	}


	//
	//
	// TESTS
	//
	//


	/*
	 * Shortcode
	 *
	 * [custom_field field="" post_id="" this_post="" random="" limit="" before="" after="" none="" between="" before_last="" id="" class=""]
	 */

	public function test_shortcode_class_exists() {
		$this->assertTrue( class_exists( 'c2c_GetCustomFieldValuesShortcode' ) );
	}

	public function test_shortcode_version() {
		$this->assertEquals( '007', c2c_GetCustomFieldValuesShortcode::version() );
	}

	public function test_shortcode_instance() {
		$this->assertTrue( is_a( c2c_GetCustomFieldValuesShortcode::$instance, 'c2c_GetCustomFieldValuesShortcode' ) );
	}

	public function test_shortcode_hooks_init() {
		$this->assertEquals( 11, has_filter( 'init', array( 'c2c_GetCustomFieldValuesShortcode', 'register' ) ) );
	}

	public function test_shortcode_with_field() {
		$post_id = $this->create_post_with_meta();
		$GLOBALS['post'] = $post_id;

		$this->assertEquals( 'happy', do_shortcode( '[custom_field field="mood"]' ) );
	}

	public function test_shortcode_with_field_and_no_global_post() {
		$post_id = $this->create_post_with_meta();

		$this->assertEmpty( do_shortcode( '[custom_field field="mood"]' ) );
	}

	public function test_shortcode_with_field_and_id_and_class() {
		$post_id = $this->create_post_with_meta();
		$GLOBALS['post'] = $post_id;

		$this->assertEquals( '<span id="the-id" class="the-class">happy</span>', do_shortcode( '[custom_field field="mood" id="the-id" class="the-class"]' ) );
	}

	public function test_shortcode_with_field_and_id() {
		$post_id = $this->create_post_with_meta();
		$GLOBALS['post'] = $post_id;

		$this->assertEquals( '<span id="the-id">happy</span>', do_shortcode( '[custom_field field="mood" id="the-id"]' ) );
	}

	public function test_shortcode_with_field_and_class() {
		$post_id = $this->create_post_with_meta();
		$GLOBALS['post'] = $post_id;

		$this->assertEquals( '<span class="the-class">happy</span>', do_shortcode( '[custom_field field="mood" class="the-class"]' ) );
	}

	public function test_shortcode_with_field_and_no_id() {
		$post_id1 = $this->create_post_with_meta();
		$post_id2 = $this->create_post_with_meta( array( 'mood' => 'pleased' ) );
		$GLOBALS['post'] = $post_id2;

		$this->assertEquals( 'pleased', do_shortcode( '[custom_field field="mood" between=" and "]' ) );
	}

	public function test_shortcode_with_field_and_no_id_and_not_this_post() {
		$post_id1 = $this->create_post_with_meta();
		$post_id2 = $this->create_post_with_meta( array( 'mood' => 'pleased' ) );

		$this->assertEquals( 'happy and pleased', do_shortcode( '[custom_field field="mood" between=" and " this_post="0"]' ) );
	}

	public function test_shortcode_with_this_post() {
		$post_id = $this->create_post_with_meta();
		$GLOBALS['post'] = $post_id;

		$this->assertEquals( 'happy', do_shortcode( '[custom_field field="mood" this_post="1"]' ) );
	}

	public function test_shortcode_with_post_id() {
		$post_id1 = $this->create_post_with_meta();
		$post_id2 = $this->create_post_with_meta( array( 'mood' => 'tired' ) );
		$GLOBALS['post'] = $post_id1;

		$this->assertEquals( 'happy', do_shortcode( '[custom_field field="mood" post_id="' . $post_id1 . '"]' ) );
		$this->assertEquals( 'tired', do_shortcode( '[custom_field field="mood" post_id="' . $post_id2 . '"]' ) );
	}

	public function test_shortcode_with_limit_and_before_and_after_and_between_and_before_last() {
		$post_id = $this->create_post_with_meta();
		$GLOBALS['post'] = $post_id;

		// Note: Limit is ignored in this scenario.
		$this->assertEquals(
			'Kids: adam, bob, cerise, and diane!',
			do_shortcode( '[custom_field field="child" post_id="" limit="3" before="Kids: " after="!" between=", " before_last=", and "]' )
		);
	}

	public function test_shortcode_with_limit_and_before_and_after_and_between_and_before_last_and_not_this_post() {
		$post_id = $this->create_post_with_meta();

		$this->assertEquals(
			'Kids: adam, bob, and cerise!',
			do_shortcode( '[custom_field field="child" post_id="" this_post="0" limit="3" before="Kids: " after="!" between=", " before_last=", and "]' )
		);
	}

	public function test_shortcode_with_double_quotes_in_attribute() {
		$post_id = $this->create_post_with_meta();
		$GLOBALS['post'] = $post_id;

		$this->assertEquals(
			'<strong class="url">example.com</strong>',
			do_shortcode( '[custom_field field="Website" this_post="1" limit="0" before=\'<strong class="url">\' after="</strong>" between=", " /]' )
		);
	}

	public function test_shortcode_with_single_quotes_in_attribute() {
		$post_id = $this->create_post_with_meta();
		$GLOBALS['post'] = $post_id;

		$this->assertEquals(
			"<strong class='url'>example.com</strong>",
			do_shortcode( '[custom_field field="Website" this_post="1" limit="0" before="<strong class=\'url\'>" after="</strong>" between=", " /]' )
		);
	}

	public function test_shortcode_with_percent_substitutions() {
		$post_id = $this->create_post_with_meta();
		$GLOBALS['post'] = $post_id;

		$this->assertEquals(
			'<li><strong>Website:</strong><a href="http://example.com" target="_blank">example.com</a>',
			do_shortcode( '[custom_field field="Website" this_post="1" limit="0" before=\'<li><strong>Website:</strong><a href="http://%value%" target="_blank">\' after="</a>" between=", " /]' )
		);
		$this->assertEquals(
			'<li><strong>Posted in:</strong> Denver, CO (location)',
			do_shortcode( '[custom_field field="location" this_post="1" limit="0" before="<li><strong>Posted in:</strong> " after=" (%field%)" /]' )
		);
	}

	// Note this currently only tests custom markup amended to the widget handler's form()
	public function test_shortcode_form() {
		$expected = '<p class="submit">'
			. '<input type="button" class="button-primary" onclick="return admin_shortcode_get_custom_field_values.sendToEditor(this.form);" value="Send shortcode to editor" />'
			. '</p>';

		$this->expectOutputRegex( '~' . preg_quote( $expected ) . '~', c2c_GetCustomFieldValuesShortcode::$instance->form() );
	}

	/*
	 * can_author_use_shortcodes()
	 */

	public function test_can_author_use_shortcodes_no_args_uses_current_post() {
		$contributor_id = $this->create_user( false, array( 'role' => 'contributor' ) );
		$post1_id       = $this->create_post_with_meta( array(), array( 'post_author' => $contributor_id ), true );

		$this->assertFalse( c2c_GetCustomFieldValuesShortcode::$instance->can_author_use_shortcodes() );

		$editor_id = $this->create_user( false, array( 'role' => 'editor' ) );
		$post2_id  = $this->create_post_with_meta( array(), array( 'post_author' => $editor_id ), true );

		$this->assertTrue( c2c_GetCustomFieldValuesShortcode::$instance->can_author_use_shortcodes() );
	}

	public function test_can_author_use_shortcodes_with_user() {
		$contributor_id = $this->create_user( false, array( 'role' => 'contributor' ) );

		$this->assertFalse( c2c_GetCustomFieldValuesShortcode::$instance->can_author_use_shortcodes( $contributor_id ) );
		$this->assertFalse( c2c_GetCustomFieldValuesShortcode::$instance->can_author_use_shortcodes( get_userdata( $contributor_id ) ) );

		$editor_id = $this->create_user( false, array( 'role' => 'editor' ) );

		$this->assertTrue( c2c_GetCustomFieldValuesShortcode::$instance->can_author_use_shortcodes( $editor_id ) );
		$this->assertTrue( c2c_GetCustomFieldValuesShortcode::$instance->can_author_use_shortcodes( get_userdata( $editor_id ) ) );
	}

	public function test_can_author_use_shortcodes_with_post() {
		$contributor_id = $this->create_user( false, array( 'role' => 'contributor' ) );
		$post1_id = $this->create_post_with_meta( array(), array( 'post_author' => $contributor_id ), true );

		$this->assertFalse( c2c_GetCustomFieldValuesShortcode::$instance->can_author_use_shortcodes( null, $post1_id ) );
		$this->assertFalse( c2c_GetCustomFieldValuesShortcode::$instance->can_author_use_shortcodes( null, get_post( $post1_id ) ) );

		$editor_id = $this->create_user( false, array( 'role' => 'editor' ) );
		$post2_id  = $this->create_post_with_meta( array(), array( 'post_author' => $editor_id ), true );

		$this->assertTrue( c2c_GetCustomFieldValuesShortcode::$instance->can_author_use_shortcodes( null, $post2_id ) );
		$this->assertTrue( c2c_GetCustomFieldValuesShortcode::$instance->can_author_use_shortcodes( null, get_post( $post2_id ) ) );
	}

	public function test_can_author_use_shortcodes_uses_user_if_it_and_post_are_provided() {
		$contributor_id = $this->create_user( false, array( 'role' => 'contributor' ) );
		$editor_id      = $this->create_user( false, array( 'role' => 'editor' ) );

		$post1_id = $this->create_post_with_meta( array(), array( 'post_author' => $editor_id ), true );

		$this->assertFalse( c2c_GetCustomFieldValuesShortcode::$instance->can_author_use_shortcodes( $contributor_id, $post1_id ) );
		$this->assertFalse( c2c_GetCustomFieldValuesShortcode::$instance->can_author_use_shortcodes( get_userdata( $contributor_id ), $post1_id ) );

		$post2_id = $this->create_post_with_meta( array(), array( 'post_author' => $contributor_id ), true );

		$this->assertTrue( c2c_GetCustomFieldValuesShortcode::$instance->can_author_use_shortcodes( $editor_id, $post2_id ) );
		$this->assertTrue( c2c_GetCustomFieldValuesShortcode::$instance->can_author_use_shortcodes( get_userdata( $editor_id ), $post2_id ) );
	}

	/*
	 * filter: get_custom_field_values/can_author_use_shortcodes
	 */

	public function test_filter_can_author_use_shortcodes() {
		add_filter( 'get_custom_field_values/can_author_use_shortcodes', function( $can, $user, $post ) {
			return true;
		}, 10, 3 );

		$contributor_id = $this->create_user( false, array( 'role' => 'contributor' ) );
		$post1_id       = $this->create_post_with_meta( array(), array( 'post_author' => $contributor_id ), true );

		$this->assertTrue( c2c_GetCustomFieldValuesShortcode::$instance->can_author_use_shortcodes() );
	}

	public function test_filter_can_author_use_shortcodes_takes_into_account_user() {
		add_filter( 'get_custom_field_values/can_author_use_shortcodes', function( $can, $user, $post ) {
			if ( $user && 'testadmin' === $user->user_nicename ) {
				$can = false;
			}
			return $can;
		}, 10, 3 );

		$admin1_id = $this->create_user( false, array( 'role' => 'administrator', 'user_nicename' => 'testadmin' ) );
		$admin2_id = $this->create_user( false, array( 'role' => 'administrator', 'user_nicename' => 'anotheradmin' ) );

		$this->assertFalse( c2c_GetCustomFieldValuesShortcode::$instance->can_author_use_shortcodes( $admin1_id ) );
		$this->assertTrue( c2c_GetCustomFieldValuesShortcode::$instance->can_author_use_shortcodes( $admin2_id ) );
	}

	/*
	 * show_metabox()
	 */

	public function test_show_metabox_when_not_in_block_editor() {
		set_current_screen( 'post.php' );
		$current_screen = get_current_screen();
		$current_screen->is_block_editor = false;

		$this->assertTrue( c2c_GetCustomFieldValuesShortcode::$instance->show_metabox() );
	}

	public function test_show_metabox_when_in_block_editor() {
		set_current_screen( 'post.php' );
		$current_screen = get_current_screen();
		$current_screen->is_block_editor = true;

		$this->assertFalse( c2c_GetCustomFieldValuesShortcode::$instance->show_metabox() );
	}

	public function test_show_metabox_when_author_cannot_publish_posts() {
		$this->test_show_metabox_when_not_in_block_editor();

		$contributor_id = $this->create_user( false, array( 'role' => 'contributor' ) );
		$post1_id       = $this->create_post_with_meta( array(), array( 'post_author' => $contributor_id ), true );

		$this->assertFalse( c2c_GetCustomFieldValuesShortcode::$instance->show_metabox() );
	}

	public function test_show_metabox_when_author_can_publish_posts() {
		$this->test_show_metabox_when_not_in_block_editor();

		$editor_id = $this->create_user( false, array( 'role' => 'editor' ) );
		$post_id   = $this->create_post_with_meta( array(), array( 'post_author' => $editor_id ), true );

		$this->assertTrue( c2c_GetCustomFieldValuesShortcode::$instance->show_metabox() );
	}
}