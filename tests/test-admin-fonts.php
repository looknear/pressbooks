<?php

require_once( PB_PLUGIN_DIR . 'inc/admin/fonts/namespace.php' );

use Pressbooks\Container;

class Admin_FontsTest extends \WP_UnitTestCase {

	use utilsTrait;

	/**
	 * Override
	 */
	public function setUp() {

		parent::setUp();

		// Replace GlobalTypography service with mock
		Container::set(
			'GlobalTypography', function () {

				$stub = $this->getMockBuilder( '\Pressbooks\GlobalTypography' )
						 ->getMock();

				return $stub;
			}
		);

		// Replace Sass service with mock
		Container::set(
			'Sass', function () {

				$stub = $this->getMockBuilder( '\Pressbooks\Sass' )
						 ->getMock();

				$stub->method( 'pathToUserGeneratedCss' )
				 ->willReturn( $this->_createTmpDir() );

				$stub->method( 'pathToPartials' )
				 ->willReturn( WP_CONTENT_DIR . '/themes/pressbooks-book/assets/legacy/styles' );

				return $stub;
			}
		);
	}


	/**
	 * Override
	 */
	public function tearDown() {

		Container::init(); // Reset
		parent::tearDown();
	}

	public function test_update_font_stacks() {

		\Pressbooks\Admin\Fonts\update_font_stacks();
		$this->assertTrue( true );
	}

	public function test_fix_missing_font_stacks() {

		$this->_book();
		\Pressbooks\Admin\Fonts\fix_missing_font_stacks();
		$this->assertTrue( true );
	}


}
