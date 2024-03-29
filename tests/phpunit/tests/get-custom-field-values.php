<?php

defined( 'ABSPATH' ) or die();

class Get_Custom_Field_Values_Test extends WP_UnitTestCase {

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

		// Obtain a random color, but retry up to 5 times if same color gets chosen.
		$i = 0;
		do {
			$random_2 = c2c_get_random_custom( 'color' );
		} while ( $i++ < 6 && $random_1 === $random_2 );

		$this->assertNotEquals( $random_1, $random_2 ); // Might fail in the highly unlinkely but possible case the same item was randomly selected 6 times in a row
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

		// Obtain a random child, but retry up to 5 times if same child gets chosen.
		$i = 0;
		do {
			$random_2 = c2c_get_random_post_custom( $post_id, 'child', 2, 'Children: ', '.', 'none', ' and ' );
		} while ( $i++ < 6 && $random_1 === $random_2 );

		$this->assertNotEquals( $random_1, $random_2 ); // Might fail in the highly unlinkely but possible case the same item was randomly selected 6 times in a row
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

}
