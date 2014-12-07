<?php 

namespace SourceQuery\Tests;

use SourceQuery\Configuration;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test configuration construct
	 */
	public function testConstruct()
	{
		$conf = new Configuration( [ 'ip' => '3.4.5.6' ] );
		$this->assertInstanceOf( 'SourceQuery\Configuration', $conf );
		$this->assertEquals( '3.4.5.6', $conf->ip );
		
		// check if timeout is set
		$this->assertEquals( 3, $conf->timeout );
	}
}