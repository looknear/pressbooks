<?php

class UtilityTest extends \WP_UnitTestCase {


	public function test_getset() {

		$array = [ 'hello' => 'world' ];
		$this->assertEquals( \Pressbooks\Utility\getset( $array, 'hello' ), 'world' );
		$this->assertEquals( \Pressbooks\Utility\getset( $array, 'nothing' ), null );
		$this->assertEquals( \Pressbooks\Utility\getset( $array, 'nothing', 'something' ), 'something' );

		global $fake_out;
		$fake_out['hello'] = 'world';
		$this->assertEquals( \Pressbooks\Utility\getset( 'fake_out', 'hello' ), 'world' );
		$this->assertEquals( \Pressbooks\Utility\getset( 'fake_out', 'nothing' ), null );
		$this->assertEquals( \Pressbooks\Utility\getset( 'fake_out', 'nothing', 'something' ), 'something' );

		$_POST['hello'] = 'world';
		$this->assertEquals( \Pressbooks\Utility\getset( '_POST', 'hello' ), 'world' );
		$this->assertEquals( \Pressbooks\Utility\getset( '_POST', 'nothing' ), null );
		$this->assertEquals( \Pressbooks\Utility\getset( '_POST', 'nothing', 'something' ), 'something' );
	}


	public function test_scandir_by_date() {

		$files = \Pressbooks\Utility\scandir_by_date( __DIR__ );

		$this->assertTrue( is_array( $files ) );
		$this->assertContains( basename( __FILE__ ), $files );
		$this->assertNotContains( '.htaccess', $files );
	}


	public function test_group_exports() {

		$files = \Pressbooks\Utility\group_exports();
		$this->assertTrue( is_array( $files ) );

		$files = \Pressbooks\Utility\group_exports( __DIR__ );
		$this->assertNotContains( '.htaccess', $files );
	}


	//  public function test_truncate_exports() {
	//      // TODO: Testing this as-is would delete files. Need to refactor to allow mocking the file system.
	//      $this->markTestIncomplete();
	//  }

	public function test_get_media_prefix() {

		$prefix = \Pressbooks\Utility\get_media_prefix();

		$this->assertTrue(
			false !== strpos( $prefix, '/blogs.dir/' ) || false !== strpos( $prefix, '/uploads/sites/' )
		);
	}

	public function test_get_media_path() {

		$guid = 'http://pressbooks.dev/test/wp-content/uploads/sites/3/2015/11/foobar.jpg';

		$path = \Pressbooks\Utility\get_media_path( $guid );

		$this->assertStringStartsWith( WP_CONTENT_DIR, $path );
		$this->assertStringEndsWith( 'foobar.jpg', $path );
		$this->assertTrue(
			false !== strpos( $path, '/blogs.dir/' ) || false !== strpos( $path, '/uploads/sites/' )
		);
	}

	public function test_add_sitemap_to_robots_txt_0() {

		update_option( 'blog_public', 0 );
		$this->expectOutputRegex( '/^\s*$/' ); // string is empty or has only whitespace
		\Pressbooks\Utility\add_sitemap_to_robots_txt();
	}

	public function test_add_sitemap_to_robots_txt_1() {

		update_option( 'blog_public', 1 );
		$this->expectOutputRegex( '/Sitemap:(.+)feed=sitemap.xml/' );
		\Pressbooks\Utility\add_sitemap_to_robots_txt();
	}

	public function test_do_sitemap_0() {

		update_option( 'blog_public', 0 );
		$this->expectOutputRegex( '/404 Not Found/i' );
		\Pressbooks\Utility\do_sitemap();
	}

	public function test_do_sitemap_1() {

		update_option( 'blog_public', 1 );
		$this->expectOutputRegex( '/^<\?xml /' );
		\Pressbooks\Utility\do_sitemap();
	}

	public function test_create_tmp_file() {

		$file = \Pressbooks\Utility\create_tmp_file();
		$this->assertFileExists( $file );

		file_put_contents( $file, 'Hello world!' );
		$this->assertEquals( 'Hello world!', file_get_contents( $file ) );
	}

	public function test_check_prince_install() {

		$this->assertInternalType( 'bool', \Pressbooks\Utility\check_prince_install() );
		$this->assertTrue( defined( 'PB_PRINCE_COMMAND' ) );
	}

	public function test_check_epubcheck_install() {

		$this->assertInternalType( 'bool', \Pressbooks\Utility\check_epubcheck_install() );
		$this->assertTrue( defined( 'PB_EPUBCHECK_COMMAND' ) );
	}

	public function test_check_kindlegen_install() {

		$this->assertInternalType( 'bool', \Pressbooks\Utility\check_kindlegen_install() );
		$this->assertTrue( defined( 'PB_KINDLEGEN_COMMAND' ) );
	}

	public function test_check_xmllint_install() {

		$this->assertInternalType( 'bool', \Pressbooks\Utility\check_xmllint_install() );
		$this->assertTrue( defined( 'PB_XMLLINT_COMMAND' ) );
	}

	public function test_check_saxonhe_install() {

		$this->assertInternalType( 'bool', \Pressbooks\Utility\check_saxonhe_install() );
		$this->assertTrue( defined( 'PB_SAXON_COMMAND' ) );
	}

	public function test_show_experimental_features() {

		$this->assertInternalType( 'bool', \Pressbooks\Utility\show_experimental_features() );
		$this->assertInternalType( 'bool', \Pressbooks\Utility\show_experimental_features( 'http://pressbooks.com' ) );

	}

	public function test_include_plugins() {

		\Pressbooks\Utility\include_plugins();
		$this->assertTrue( class_exists( 'custom_metadata_manager' ) );
	}

	public function test_filter_plugins() {

		$symbionts = [ 'a-plugin-that-does-not-exist/foobar.php' => 1 ];

		$filtered = \Pressbooks\Utility\filter_plugins( $symbionts );

		$this->assertTrue( is_array( $filtered ) );
		$this->assertArrayHasKey( 'a-plugin-that-does-not-exist/foobar.php', $filtered );
	}

	public function test_file_upload_max_size() {

		$maxSize = \Pressbooks\Utility\file_upload_max_size();

		$this->assertTrue(
			ini_get( 'post_max_size' ) == $maxSize || ini_get( 'upload_max_filesize' ) == $maxSize
		);

	}

	public function test_parse_size() {

		$this->assertTrue( is_float( \Pressbooks\Utility\parse_size( '1' ) ) );

		$this->assertEquals( 65536, \Pressbooks\Utility\parse_size( '64K' ) );
		$this->assertEquals( 2097152, \Pressbooks\Utility\parse_size( '2M' ) );
		$this->assertEquals( 8388608, \Pressbooks\Utility\parse_size( '8M' ) );
	}

	public function test_format_bytes() {

		$this->assertEquals( '200 B', \Pressbooks\Utility\format_bytes( 200 ) );
		$this->assertEquals( '200 B', \Pressbooks\Utility\format_bytes( 200, 4 ) );

		$this->assertEquals( '1.95 KB', \Pressbooks\Utility\format_bytes( 2000 ) );
		$this->assertEquals( '1.9531 KB', \Pressbooks\Utility\format_bytes( 2000, 4 ) );

		$this->assertEquals( '1.91 MB', \Pressbooks\Utility\format_bytes( 2000000 ) );
		$this->assertEquals( '1.9073 MB', \Pressbooks\Utility\format_bytes( 2000000, 4 ) );

		$this->assertEquals( '1.86 GB', \Pressbooks\Utility\format_bytes( 2000000000 ) );
		$this->assertEquals( '1.8626 GB', \Pressbooks\Utility\format_bytes( 2000000000, 4 ) );

		$this->assertEquals( '1.82 TB', \Pressbooks\Utility\format_bytes( 2000000000000 ) );
		$this->assertEquals( '1.819 TB', \Pressbooks\Utility\format_bytes( 2000000000000, 4 ) );
	}


	//  public function test_email_error_log() {
	//      // TODO: Testing this as-is would send emails, write to error_log... Need to refactor
	//      $this->markTestIncomplete();
	//  }


	public function test_template() {

		$template = \Pressbooks\Utility\template(
			__DIR__ . '/data/template.php',
			[ 'title' => 'Foobar', 'body' => 'Hello World!' ]
		);

		$this->assertContains( '<title>Foobar</title>', $template );
		$this->assertNotContains( '<title></title>', $template );

		$this->assertContains( '<body>Hello World!</body>', $template );
		$this->assertNotContains( '<body></body>', $template );

		try {
			\Pressbooks\Utility\template( '/tmp/file/does/not/exist' );
		} catch ( \Exception $e ) {
			$this->assertTrue( true );
			return;
		}
		$this->fail();
	}

	public function test_mail_from() {
		$this->assertEquals( 'pressbooks@example.org', \Pressbooks\Utility\mail_from( '' ) );
		define( 'WP_MAIL_FROM', 'hi@pressbooks.org' );
		$this->assertEquals( 'hi@pressbooks.org', \Pressbooks\Utility\mail_from( '' ) );
	}

	public function test_mail_from_name() {
		$this->assertEquals( 'Pressbooks', \Pressbooks\Utility\mail_from_name( '' ) );
		define( 'WP_MAIL_FROM_NAME', 'Ned' );
		$this->assertEquals( 'Ned', \Pressbooks\Utility\mail_from_name( '' ) );
	}

	public function test_rcopy() {
		$uploads = wp_upload_dir();
		$src = trailingslashit( $uploads['path'] ) . 'src';
		$dest = trailingslashit( $uploads['path'] ) . 'dest';
		@mkdir( $src );
		file_put_contents( $src . '/test.txt', 'test' );

		$return = \Pressbooks\Utility\rcopy( $src, $dest );
		$contents = file_get_contents( $dest . '/test.txt' );
		$this->assertTrue( $return );
		$this->assertEquals( 'test', $contents );

		$return = \Pressbooks\Utility\rcopy( trailingslashit( $uploads['path'] ) . 'missing', $dest );
		$this->assertEquals( $return, false );
	}

	public function test_str_starts_with() {
		$this->assertTrue( \Pressbooks\Utility\str_starts_with( 's0.wp.com', 's0.wp' ) );
		$this->assertFalse( \Pressbooks\Utility\str_starts_with( 's0.wp.com', 'wp.com' ) );
	}

	public function test_str_ends_with() {
		$this->assertFalse( \Pressbooks\Utility\str_ends_with( 's0.wp.com', 's0.wp' ) );
		$this->assertTrue( \Pressbooks\Utility\str_ends_with( 's0.wp.com', 'wp.com' ) );
	}

}
