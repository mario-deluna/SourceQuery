<?php 

namespace SourceQuery\Tests;

use SourceQuery\Client;

class ClientTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Tests client construct
	 */
	public function testConstruct()
	{
		$client = new Client( '1.2.3.4', 27015, true, 'SourceQuery\\Test\\Connection' );
		
		$this->assertInstanceOf( 'SourceQuery\\Client', $client );
		$this->assertInstanceOf( 'SourceQuery\\Connection', $client->connection() );
		$this->assertInstanceOf( 'SourceQuery\\Configuration', $client->config() );
		$this->assertInstanceOf( 'SourceQuery\\Server', $client->server() );
		
		var_dump( $client );
		
		
		
	}

}