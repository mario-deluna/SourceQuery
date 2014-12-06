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
		$client = new Client( '89.223.32.156', '27012' );
		$client->server();
		
		var_dump( $client->server() );
		
		
		
	}

}