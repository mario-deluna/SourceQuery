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
		$conf = new Configuration;
		$this->assertInstanceOf( 'SourceQuery\Configuration', $conf );
	}
}