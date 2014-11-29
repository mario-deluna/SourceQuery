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
		$client = new Client( '192.223.31.94' );
		$client->server();
		
		print_r( $client );
		
		
		
	}

}