<?php

defined( 'ABSPATH' ) or die();

class Get_Custom_Field_Values_Widget_Test extends WP_UnitTestCase {

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

	private function widget_init( $config = array() ) {
		$post_id = $this->create_post_with_meta();

		c2c_GetCustomWidget::register_widget();
		$widget = new c2c_GetCustomWidget( 'abc_abc', '', array() );

		$default_config = array();
		foreach ( $widget->get_config() as $key => $val ) {
			$default_config[ $key ] = $val['default'];
		}
		$config = array_merge( $default_config, $config );

		if ( true === $config['post_id'] ) {
			$config['post_id'] = $post_id;
		}

		$settings = array( 'before_title' => '', 'after_title' => '', );

		return array( $post_id, $widget, $config, $settings );
	}


	//
	//
	// TESTS
	//
	//


	public function test_widget_class_exists() {
		$this->assertTrue( class_exists( 'c2c_GetCustomWidget' ) );
	}

	public function test_widget_version() {
		$this->assertEquals( '012', c2c_GetCustomWidget::version() );
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
		$this->assertArrayHasKey( 'c2c_GetCustomWidget', $GLOBALS['wp_widget_factory']->widgets );
	}

	public function test_widget_body_with_class() {
		list( $post_id, $widget, $config, $settings ) = $this->widget_init( array( 'field' => 'mood', 'class' => 'abcd', 'post_id' => true )  );

		$this->assertEquals( '<span class="abcd">happy</span>', $widget->widget_body( $config, '', $settings ) );
	}

	public function test_widget_body_with_id() {
		list( $post_id, $widget, $config, $settings ) = $this->widget_init( array( 'field' => 'mood', 'id' => 'myid', 'post_id' => true ) );

		$this->assertEquals( '<span id="myid">happy</span>', $widget->widget_body( $config, '', $settings ) );
	}

	public function test_widget_body_with_class_and_id() {
		list( $post_id, $widget, $config, $settings ) = $this->widget_init( array( 'field' => 'mood', 'class' => 'abcd', 'id' => 'myid', 'post_id' => true ) );

		$this->assertEquals( '<span id="myid" class="abcd">happy</span>', $widget->widget_body( $config, '', $settings ) );
	}

	public function test_widget_body_with_class_and_id_but_no_meta_value() {
		list( $post_id, $widget, $config, $settings ) = $this->widget_init( array( 'field' => 'nonexistent', 'class' => 'abcd', 'id' => 'myid', 'post_id' => true ) );

		$this->assertEmpty( $widget->widget_body( $config, '', $settings ) );
	}

	public function test_widget_with_post_id_of_current_in_invalid_situation() {
		$p_id = $this->create_post_with_meta( array( 'mood' => 'confused' ) );

		list( $post_id, $widget, $config, $settings ) = $this->widget_init( array( 'field' => 'mood', 'post_id' => 'current' ) );

		$this->assertEmpty( $widget->widget_body( $config, '', $settings ) );
	}

	public function test_widget_with_post_id_of_current() {
		$p_id = $this->create_post_with_meta( array( 'mood' => 'perplexed' ) );

		// Simulate conditions when it is valid to run.
		query_posts( array( 'p' => $p_id ) );

		list( $post_id, $widget, $config, $settings ) = $this->widget_init( array( 'field' => 'mood', 'post_id' => 'current' ) );

		$this->assertEquals( 'perplexed', $widget->widget_body( $config, '', $settings ) );
	}

	public function test_widget_with_explicit_post_id() {
		$p_id = $this->create_post_with_meta( array( 'mood' => 'joyous' ) );

		list( $post_id, $widget, $config, $settings ) = $this->widget_init( array( 'field' => 'mood', 'post_id' => $p_id ) );

		query_posts( array( 'p' => $post_id ) );

		$this->assertEquals( 'joyous', $widget->widget_body( $config, '', $settings ) );
	}

	public function test_widget_with_no_post_id() {
		list( $post_id, $widget, $config, $settings ) = $this->widget_init( array( 'field' => 'mood' ) );

		query_posts( array( 'p' => $post_id ) );

		$this->assertEquals( 'happy', $widget->widget_body( $config, '', $settings ) );
	}

	public function test_widget_with_no_post_id_and_no_current_post() {
		$post_id = $this->create_post_with_meta( array( 'mood' => 'befuddled' ) );
		list( $post_id, $widget, $config, $settings ) = $this->widget_init( array( 'field' => 'mood', 'between' => ' and ' ) );

		$this->assertEquals( 'befuddled and happy', $widget->widget_body( $config, '', $settings ) );
	}

	public function test_widget_with_html_in_meta() {
		$post_id = $this->create_post_with_meta( array( 'concert' => '<sCript>alert(document.domain)</sCript>' ) );
		list( $post_id, $widget, $config, $settings ) = $this->widget_init( array( 'field' => 'concert', 'before' => '<strong>', 'after' => '</strong>', 'none' => 'None' ) );

		$this->assertEquals( "<strong>alert(document.domain)</strong>", $widget->widget_body( $widget->validate( $config ), '', $settings ) );
	}

	public function test_widget_with_html_in_fields() {
		$post_id = $this->create_post_with_meta( array( 'moodx' => '', [], true ) );
		$script = 'hello<sCript>alert(document.domain)</sCript>';
		$safe_script = '<strong>None found.</strong>';
		list( $post_id, $widget, $config, $settings ) = $this->widget_init( array( 'field' => 'moodx', 'before' => $script, 'after' => $script, 'none' => $safe_script ) );

		$this->assertEquals( "helloalert(document.domain)${safe_script}helloalert(document.domain)", $widget->widget_body( $widget->validate( $config ), '', $settings ) );
	}

}
