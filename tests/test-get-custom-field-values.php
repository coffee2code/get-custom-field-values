<?php

defined( 'ABSPATH' ) or die();

class Get_Custom_Field_Values_Test extends WP_UnitTestCase {

	//
	//
	// HELPER FUNCTIONS
	//
	//


	private function create_post_with_meta( $metas = array(), $post_data = array() ) {
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
		);
	}


	//
	//
	// TESTS
	//
	//


	/* c2c_get_custom( $field, $before='', $after='', $none='', $between='', $before_last='' ) */

	public function test_c2c_get_custom_with_field() {
		$post_id = $this->create_post_with_meta();
		$GLOBALS['post'] = $post_id;

		$this->assertEquals( 'happy',      c2c_get_custom( 'mood' ) );
		$this->assertEquals( 'Denver, CO', c2c_get_custom( 'location' ) );
		$this->assertEmpty( c2c_get_custom( 'nonexistent' ) );
	}

	public function test_c2c_get_custom_with_serialized_field() {
		$post_id = $this->create_post_with_meta();
		$GLOBALS['post'] = $post_id;

		$values = array( 'Value1', 'Value2' );
		add_post_meta( $post_id, 'serialized', $values );

		$this->assertEquals( implode( ', ', $values ), c2c_get_custom( 'serialized', '', '', '', ', ' ) );
	}

	public function test_c2c_get_custom_with_multple_serialized_fields() {
		$post_id = $this->create_post_with_meta();
		$GLOBALS['post'] = $post_id;

		$values1 = array( 'Value1', 'Value2' );
		add_post_meta( $post_id, 'serialized', $values1 );
		$values2 = array( 'Value3', 'Value4' );
		add_post_meta( $post_id, 'serialized', $values2 );
		add_post_meta( $post_id, 'serialized', 'Value5' );

		$this->assertEquals(
			implode( ', ', array_merge( $values1, $values2, array( 'Value5' ) ) ),
			c2c_get_custom( 'serialized', '', '', '', ', ' )
		);
	}

	public function test_c2c_get_custom_with_before() {
		$post_id = $this->create_post_with_meta();
		$GLOBALS['post'] = $post_id;

		$this->assertEquals( 'Mood: happy', c2c_get_custom( 'mood', 'Mood: ' ) );
		$this->assertEmpty( c2c_get_custom( 'nonexistent', 'Mood: ' ) );
	}

	public function test_c2c_get_custom_with_after() {
		$post_id = $this->create_post_with_meta();
		$GLOBALS['post'] = $post_id;

		$this->assertEquals( 'Mood: happy!', c2c_get_custom( 'mood', 'Mood: ', '!' ) );
		$this->assertEmpty( c2c_get_custom( 'nonexistent', 'Mood: ', '!' ) );
	}

	public function test_c2c_get_custom_with_none() {
		$post_id = $this->create_post_with_meta();
		$GLOBALS['post'] = $post_id;

		$this->assertEquals( 'Mood: happy!',   c2c_get_custom( 'mood', 'Mood: ', '!', 'unknown' ) );
		$this->assertEquals( 'Mood: unknown!', c2c_get_custom( 'nonexistent', 'Mood: ', '!', 'unknown' ) );
	}

	public function test_c2c_get_custom_with_between_for_single_value() {
		$post_id = $this->create_post_with_meta();
		$GLOBALS['post'] = $post_id;

		$this->assertEquals( 'Mood: happy!', c2c_get_custom( 'mood', 'Mood: ', '!', 'unknown', ', ' ) );
		$this->assertEmpty( c2c_get_custom( 'nonexistent', 'Mood: ', '!', '', ', ' ) );
	}

	public function test_c2c_get_custom_with_between_for_two_values() {
		$post_id = $this->create_post_with_meta();
		$GLOBALS['post'] = $post_id;

		$this->assertEquals( 'Colors: blue, white.', c2c_get_custom( 'color', 'Colors: ', '.', 'none', ', ' ) );
	}

	public function test_c2c_get_custom_with_between_for_multiple_values() {
		$post_id = $this->create_post_with_meta();
		$GLOBALS['post'] = $post_id;

		$this->assertEquals( 'Children: adam, bob, cerise, diane.', c2c_get_custom( 'child', 'Children: ', '.', 'none', ', ' ) );
	}

	public function test_c2c_get_custom_with_before_last_for_single_value() {
		$post_id = $this->create_post_with_meta();
		$GLOBALS['post'] = $post_id;

		$this->assertEquals( 'Mood: happy!', c2c_get_custom( 'mood', 'Mood: ', '!', 'unknown', ', ', ', and ' ) );
		$this->assertEmpty( c2c_get_custom( 'nonexistent', 'Mood: ', '!', '', ', ', ', and' ) );
	}

	public function test_c2c_get_custom_with_before_last_for_two_values() {
		$post_id = $this->create_post_with_meta();
		$GLOBALS['post'] = $post_id;

		$this->assertEquals( 'Colors: blue, and white.', c2c_get_custom( 'color', 'Colors: ', '.', 'none', ', ', ', and ' ) );
	}

	public function test_c2c_get_custom_with_before_last_for_multiple_values() {
		$post_id = $this->create_post_with_meta();
		$GLOBALS['post'] = $post_id;

		$this->assertEquals( 'Children: adam, bob, cerise, and diane.', c2c_get_custom( 'child', 'Children: ', '.', 'none', ', ', ', and ' ) );
	}

	/* c2c_get_current_custom( $field, $before='', $after='', $none='', $between='', $before_last='' ) */

	public function test_c2c_get_current_custom_with_field() {
		$post_id = $this->create_post_with_meta();

		$this->go_to( get_permalink( $post_id ) );

		$this->assertEquals( 'happy',      c2c_get_current_custom( 'mood' ) );
		$this->assertEquals( 'Denver, CO', c2c_get_current_custom( 'location' ) );
		$this->assertEmpty( c2c_get_current_custom( 'nonexistent' ) );
	}

	public function test_c2c_get_current_custom_with_before() {
		$post_id = $this->create_post_with_meta();

		$this->go_to( get_permalink( $post_id ) );

		$this->assertEquals( 'Mood: happy', c2c_get_current_custom( 'mood', 'Mood: ' ) );
		$this->assertEmpty( c2c_get_current_custom( 'nonexistent', 'Mood: ' ) );
	}

	public function test_c2c_get_current_custom_with_after() {
		$post_id = $this->create_post_with_meta();

		$this->go_to( get_permalink( $post_id ) );

		$this->assertEquals( 'Mood: happy!', c2c_get_current_custom(  'mood', 'Mood: ', '!' ) );
		$this->assertEmpty( c2c_get_current_custom( 'nonexistent', 'Mood: ', '!' ) );
	}

	public function test_c2c_get_current_custom_with_none() {
		$post_id = $this->create_post_with_meta();

		$this->go_to( get_permalink( $post_id ) );

		$this->assertEquals( 'Mood: happy!',   c2c_get_current_custom( 'mood', 'Mood: ', '!', 'unknown' ) );
		$this->assertEquals( 'Mood: unknown!', c2c_get_current_custom( 'nonexistent', 'Mood: ', '!', 'unknown' ) );
	}

	public function test_c2c_get_current_custom_with_between_for_single_value() {
		$post_id = $this->create_post_with_meta();

		$this->go_to( get_permalink( $post_id ) );

		$this->assertEquals( 'Mood: happy!', c2c_get_current_custom( 'mood', 'Mood: ', '!', 'unknown', ', ' ) );
		$this->assertEmpty( c2c_get_current_custom( 'nonexistent', 'Mood: ', '!', '', ', ' ) );
	}

	public function test_c2c_get_current_custom_with_between_for_two_values() {
		$post_id = $this->create_post_with_meta();

		$this->go_to( get_permalink( $post_id ) );

		$this->assertEquals( 'Colors: blue, white.', c2c_get_current_custom( 'color', 'Colors: ', '.', 'none', ', ' ) );
	}

	public function test_c2c_get_current_custom_with_between_for_multiple_values() {
		$post_id = $this->create_post_with_meta();

		$this->go_to( get_permalink( $post_id ) );

		$this->assertEquals( 'Children: adam, bob, cerise, diane.', c2c_get_current_custom( 'child', 'Children: ', '.', 'none', ', ' ) );
	}

	public function test_c2c_get_current_custom_with_before_last_for_single_value() {
		$post_id = $this->create_post_with_meta();

		$this->go_to( get_permalink( $post_id ) );

		$this->assertEquals( 'Mood: happy!', c2c_get_current_custom( 'mood', 'Mood: ', '!', 'unknown', ', ', ', and ' ) );
		$this->assertEmpty( c2c_get_current_custom( 'nonexistent', 'Mood: ', '!', '', ', ', ', and' ) );
	}

	public function test_c2c_get_current_custom_with_before_last_for_two_values() {
		$post_id = $this->create_post_with_meta();

		$this->go_to( get_permalink( $post_id ) );

		$this->assertEquals( 'Colors: blue, and white.', c2c_get_current_custom( 'color', 'Colors: ', '.', 'none', ', ', ', and ' ) );
	}

	public function test_c2c_get_current_custom_with_before_last_for_multiple_values() {
		$post_id = $this->create_post_with_meta();

		$this->go_to( get_permalink( $post_id ) );

		$this->assertEquals( 'Children: adam, bob, cerise, and diane.', c2c_get_current_custom( 'child', 'Children: ', '.', 'none', ', ', ', and ' ) );
	}

	/* c2c_get_post_custom( $post_id, $field, $before='', $after='', $none='', $between='', $before_last='' ) */

	public function test_c2c_get_post_custom_with_field() {
		$post_id = $this->create_post_with_meta();

		$this->assertEquals( 'happy',      c2c_get_post_custom( $post_id, 'mood' ) );
		$this->assertEquals( 'Denver, CO', c2c_get_post_custom( $post_id, 'location' ) );
		$this->assertEmpty( c2c_get_post_custom( $post_id, 'nonexistent' ) );
	}

	public function test_c2c_get_post_custom_with_before() {
		$post_id = $this->create_post_with_meta();

		$this->assertEquals( 'Mood: happy', c2c_get_post_custom( $post_id, 'mood', 'Mood: ' ) );
		$this->assertEmpty( c2c_get_post_custom( $post_id, 'nonexistent', 'Mood: ' ) );
	}

	public function test_c2c_get_post_custom_with_after() {
		$post_id = $this->create_post_with_meta();

		$this->assertEquals( 'Mood: happy!', c2c_get_post_custom( $post_id, 'mood', 'Mood: ', '!' ) );
		$this->assertEmpty( c2c_get_post_custom( $post_id, 'nonexistent', 'Mood: ', '!' ) );
	}

	public function test_c2c_get_post_custom_with_none() {
		$post_id = $this->create_post_with_meta();

		$this->assertEquals( 'Mood: happy!',   c2c_get_post_custom( $post_id, 'mood', 'Mood: ', '!', 'unknown' ) );
		$this->assertEquals( 'Mood: unknown!', c2c_get_post_custom( $post_id, 'nonexistent', 'Mood: ', '!', 'unknown' ) );
	}

	public function test_c2c_get_post_custom_with_between_for_single_value() {
		$post_id = $this->create_post_with_meta();

		$this->assertEquals( 'Mood: happy!', c2c_get_post_custom( $post_id, 'mood', 'Mood: ', '!', 'unknown', ', ' ) );
		$this->assertEmpty( c2c_get_post_custom( $post_id, 'nonexistent', 'Mood: ', '!', '', ', ' ) );
	}

	public function test_c2c_get_post_custom_with_between_for_two_values() {
		$post_id = $this->create_post_with_meta();

		$this->assertEquals( 'Colors: blue, white.', c2c_get_post_custom( $post_id, 'color', 'Colors: ', '.', 'none', ', ' ) );
	}

	public function test_c2c_get_post_custom_with_between_for_multiple_values() {
		$post_id = $this->create_post_with_meta();

		$this->assertEquals( 'Children: adam, bob, cerise, diane.', c2c_get_post_custom( $post_id, 'child', 'Children: ', '.', 'none', ', ' ) );
	}

	public function test_c2c_get_post_custom_with_before_last_for_single_value() {
		$post_id = $this->create_post_with_meta();

		$this->assertEquals( 'Mood: happy!', c2c_get_post_custom( $post_id, 'mood', 'Mood: ', '!', 'unknown', ', ', ', and ' ) );
		$this->assertEmpty( c2c_get_post_custom( $post_id, 'nonexistent', 'Mood: ', '!', '', ', ', ', and' ) );
	}

	public function test_c2c_get_post_custom_with_before_last_for_two_values() {
		$post_id = $this->create_post_with_meta();

		$this->assertEquals( 'Colors: blue, and white.', c2c_get_post_custom( $post_id, 'color', 'Colors: ', '.', 'none', ', ', ', and ' ) );
	}

	public function test_c2c_get_post_custom_with_before_last_for_multiple_values() {
		$post_id = $this->create_post_with_meta();

		$this->assertEquals( 'Children: adam, bob, cerise, and diane.', c2c_get_post_custom( $post_id, 'child', 'Children: ', '.', 'none', ', ', ', and ' ) );
	}

	/* c2c_get_random_custom( $field, $before='', $after='', $none='', $limit=1, $between=', ', $before_last='' ) */

	public function test_c2c_get_random_custom_with_field() {
		$post_id = $this->create_post_with_meta();

		$this->assertRegExp( '/^Color: (blue|white)\.$/', c2c_get_random_custom( 'color', 'Color: ', '.' ) );
		$this->assertEmpty( c2c_get_random_custom( 'nonexistent', 'Color: ', '.' ) );
	}

	public function test_c2c_get_random_custom_with_none() {
		$post_id = $this->create_post_with_meta();

		$this->assertEquals( 'Color: none.', c2c_get_random_custom( 'nonexistent', 'Color: ', '.', 'none', 1 ) );
	}

	public function test_c2c_get_random_custom_with_multiple_values() {
		$post_id = $this->create_post_with_meta();

		$random_1 = c2c_get_random_custom( 'child', 'Children: ', '.', 'none', 2, ' and ' );
		$random_2 = c2c_get_random_custom( 'child', 'Children: ', '.', 'none', 2, ' and ' );

		$this->assertNotEquals( $random_1, $random_2 ); // It's possible the same item was randomly selected back-to-back
		$this->assertRegExp( '/^Children: (adam|bob|cerise|diane) and (adam|bob|cerise|diane)\.$/', $random_1 );
		$this->assertRegExp( '/^Children: (adam|bob|cerise|diane) and (adam|bob|cerise|diane)\.$/', $random_2 );
	}

	public function test_c2c_get_random_custom_crosses_posts() {
		$colors = array( 'green', 'purple', 'cyan', 'red', 'blue', 'orange' );

		$i = 1;
		foreach ( $colors as $color ) {
			$this->create_post_with_meta( array( 'color' => $color ),  array( 'post_date' => "2013-0{$i}-01 12:00:00" ) );
			$i++;
		}

		$random_1 = c2c_get_random_custom( 'color' );
		$random_2 = c2c_get_random_custom( 'color' );

		$this->assertFalse( $random_1 == $random_2 ); // It's possible the same item was randomly selected back-to-back
		$this->assertContains( $random_1, $colors );
		$this->assertContains( $random_2, $colors );
	}

	/* c2c_get_random_post_custom( $post_id, $field, $limit=1, $before='', $after='', $none='', $between='', $before_last='' ) */

	public function test_c2c_get_post_random_custom_with_field() {
		$post_id = $this->create_post_with_meta();

		$this->assertRegExp( '/^Color: (blue|white)\.$/', c2c_get_random_post_custom( $post_id, 'color', 1, 'Color: ', '.' ) );
		$this->assertEmpty( c2c_get_random_post_custom( $post_id, 'nonexistent', 'Color: ', '.' ) );
	}

	public function test_c2c_get_random_post_custom_with_none() {
		$post_id = $this->create_post_with_meta();

		$this->assertEquals( 'Color: none.', c2c_get_random_post_custom( $post_id, 'nonexistent', 1, 'Color: ', '.', 'none' ) );
	}

	public function test_c2c_get_random_post_custom_with_multiple_values() {
		$post_id = $this->create_post_with_meta();

		$random_1 = c2c_get_random_post_custom( $post_id, 'child', 2, 'Children: ', '.', 'none', ' and ' );
		$random_2 = c2c_get_random_post_custom( $post_id, 'child', 2, 'Children: ', '.', 'none', ' and ' );

		$this->assertNotEquals( $random_1, $random_2 ); // It's possible the same item was randomly selected back-to-back
		$this->assertRegExp( '/^Children: (adam|bob|cerise|diane) and (adam|bob|cerise|diane)\.$/', $random_1 );
		$this->assertRegExp( '/^Children: (adam|bob|cerise|diane) and (adam|bob|cerise|diane)\.$/', $random_2 );
	}

	/* c2c_get_recent_custom( $field, $before='', $after='', $none='', $between=', ', $before_last='', $limit=1, $unique=false, $order='DESC', $include_pages=true, $show_pass_post=false ) */

	public function test_c2c_get_recent_custom() {
		$post_id1 = $this->create_post_with_meta( array( 'color' => 'green' ),  array( 'post_title' => 'B', 'post_date' => '2013-02-01 12:00:00' ) );
		$post_id2 = $this->create_post_with_meta( array( 'color' => 'purple' ), array( 'post_title' => 'A', 'post_date' => '2013-03-01 12:00:00' ) );
		$post_id3 = $this->create_post_with_meta( array( 'color' => 'cyan' ),   array( 'post_title' => 'C', 'post_date' => '2013-04-01 12:00:00' ) );

		$this->assertEquals( 'cyan', c2c_get_recent_custom( 'color' ) );
	}

	public function test_c2c_get_recent_custom_for_nonexistent_field() {
		$post_id1 = $this->create_post_with_meta( array( 'color' => 'green' ),  array( 'post_date' => '2013-02-01 12:00:00' ) );
		$post_id2 = $this->create_post_with_meta( array( 'color' => 'purple' ), array( 'post_date' => '2013-03-01 12:00:00' ) );
		$post_id3 = $this->create_post_with_meta( array( 'color' => 'cyan' ),   array( 'post_date' => '2013-04-01 12:00:00' ) );

		$this->assertEmpty( c2c_get_recent_custom( 'nonexistent' ) );
		$this->assertEquals( 'none', c2c_get_recent_custom( 'nonexistent', '', '', 'none' ) );
	}

	public function test_c2c_get_recent_custom_with_order() {
		$post_id1 = $this->create_post_with_meta( array( 'color' => 'green' ),  array( 'post_title' => 'B', 'post_date' => '2013-02-01 12:00:00' ) );
		$post_id2 = $this->create_post_with_meta( array( 'color' => 'purple' ), array( 'post_title' => 'A', 'post_date' => '2013-03-01 12:00:00' ) );
		$post_id3 = $this->create_post_with_meta( array( 'color' => 'cyan' ),   array( 'post_title' => 'C', 'post_date' => '2013-04-01 12:00:00' ) );

		$this->assertEquals( 'green', c2c_get_recent_custom( 'color', '', '', '', '', '', 1, false, 'ASC' ) );
	}

	public function test_c2c_get_recent_custom_for_multiples() {
		$post_id1 = $this->create_post_with_meta( array( 'color' => 'green' ),  array( 'post_title' => 'B', 'post_date' => '2013-02-01 12:00:00' ) );
		$post_id2 = $this->create_post_with_meta( array( 'color' => 'purple' ), array( 'post_title' => 'A', 'post_date' => '2013-03-01 12:00:00' ) );
		$post_id3 = $this->create_post_with_meta( array( 'color' => 'cyan' ),   array( 'post_title' => 'C', 'post_date' => '2013-04-01 12:00:00' ) );

		$this->assertEquals( 'Colors: cyan and purple!', c2c_get_recent_custom( 'color', 'Colors: ', '!', 'none', ' and ', '', 2 ) );
	}

	/*
	 * c2c__gcfv_do_substitutions()
	 */

	public function test_c2c__gcfv_do_substitutions() {
		$this->assertEquals( 'cat and dog', c2c__gcfv_do_substitutions( 'cat and dog', 'example', 'zissou' ) );
		$this->assertEquals( 'cat and zissou', c2c__gcfv_do_substitutions( 'cat and %value%', 'example', 'zissou' ) );
		$this->assertEquals( 'cat and example', c2c__gcfv_do_substitutions( 'cat and %field%', 'example', 'zissou' ) );
		$this->assertEquals( 'example: zissou', c2c__gcfv_do_substitutions( '%field%: %value%', 'example', 'zissou' ) );
		$this->assertEquals( 'example: zissou%zissou="example"', c2c__gcfv_do_substitutions( '%field%: %value%%%value%="%field%"', 'example', 'zissou' ) );
	}

	/*
	 * Shortcode
	 *
	 * [custom_field field="" post_id="" this_post="" random="" limit="" before="" after="" none="" between="" before_last="" id="" class=""]
	 */

	public function test_shortcode_class_exists() {
		$this->assertTrue( class_exists( 'c2c_GetCustomFieldValuesShortcode' ) );
	}

	public function test_shortcode_version() {
		$this->assertEquals( '004', c2c_GetCustomFieldValuesShortcode::version() );
	}

	public function test_shortcode_hooks_init() {
		$this->assertEquals( 11, has_filter( 'init', 'register_c2c_GetCustomFieldValuesShortcode' ) );
	}

	public function test_shortcode_with_field() {
		$post_id = $this->create_post_with_meta();

		$this->assertEquals( 'happy', do_shortcode( '[custom_field field="mood"]' ) );
	}

	public function test_shortcode_with_field_and_id_and_class() {
		$post_id = $this->create_post_with_meta();
		$GLOBALS['post'] = $post_id;

		$this->assertEquals( '<span id="the-id" class="the-class">happy</span>', do_shortcode( '[custom_field field="mood" id="the-id" class="the-class"]' ) );
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

		$this->assertEquals(
			'Kids: adam, bob, and cerise!',
			do_shortcode( '[custom_field field="child" post_id="" limit="3" before="Kids: " after="!" between=", " before_last=", and "]' )
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

	/* Widget */

	public function test_widget_class_exists() {
		$this->assertTrue( class_exists( 'c2c_GetCustomWidget' ) );
	}

	public function test_widget_version() {
		$this->assertEquals( '011', c2c_GetCustomWidget::version() );
	}

	public function test_widget_base_class_name() {
		$this->assertTrue( class_exists( 'c2c_Widget_013' ) );
	}

	public function test_widget_framework_version() {
		$this->assertEquals( '013', c2c_Widget_013::version() );
	}

	public function test_widget_hooks_widgets_init() {
		$this->assertEquals( 10, has_filter( 'widgets_init', array( 'c2c_GetCustomWidget', 'register_widget' ) ) );
	}

	public function test_widget_made_available() {
		$this->assertContains( 'c2c_GetCustomWidget', array_keys( $GLOBALS['wp_widget_factory']->widgets ) );
	}

}
