<?php 

namespace SourceQuery\Tests;

use SourceQuery\Client;
use SourceQuery\Test\Connection;

class ClientTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Create new client objcet with test connection
	 */
	public function createClient()
	{
		return new Client( '1.2.3.4', 27015, true, 'SourceQuery\\Test\\Connection' );
	}
	
	/**
	 * Tests client construct
	 */
	public function testConstruct()
	{
		$client = $this->createClient();
		
		$this->assertInstanceOf( 'SourceQuery\\Client', $client );
		$this->assertInstanceOf( 'SourceQuery\\Connection', $client->connection() );
		$this->assertInstanceOf( 'SourceQuery\\Configuration', $client->config() );
		$this->assertInstanceOf( 'SourceQuery\\Server', $client->server() );
		
		// test default ip and port
		$this->assertEquals( '1.2.3.4', $client->config()->ip );	
		$this->assertEquals( 27015, $client->config()->port );	
	}
	
	/**
	 * Tests client setIp
	 */
	public function testSetIp()
	{
		$client = $this->createClient();
		
		$client->setIp( '2.3.4.5' );
		$this->assertEquals( '2.3.4.5', $client->config()->ip );
	}
	
	/**
	 * Tests client setIp with no argument
	 *
	 * @expectedException \SourceQuery\Exception
	 */
	public function testSetIpNull()
	{
		$client = $this->createClient();
		$client->setIp( null );
	}
	
	/**
	 * Tests client setIp
	 */
	public function testSetPort()
	{
		$client = $this->createClient();
		
		$client->setPort( 117 );
		$this->assertEquals( 117, $client->config()->port );
		
		// set default
		$client->setPort();
		$this->assertEquals( 27015, $client->config()->port );
	}
	
	/**
	 * Tests client connect
	 */
	public function testConnect()
	{
		// reset the number of connections
		Connection::$numberOfConnections = 0;
		
		$client = $this->createClient();
		$this->assertEquals( 1, Connection::$numberOfConnections );
		
		// this should close the connection and create a new one
		$client->connect( 'SourceQuery\\Test\\Connection' );
		$this->assertEquals( 2, Connection::$numberOfConnections );
	}
	
	/**
	 * Tests connect with invalid driver
	 * 
	 * @expectedException \SourceQuery\Exception
	 */
	public function testConnectInvalidDriver()
	{
		$client = $this->createClient();
		$client->connect( 'SourceQuery\\DataModel' );
	}
	
	/**
	 * Tests client fetch server data
	 */
	public function testServer()
	{
		// reset the number of connections
		Connection::$numberOfQueries = 0;
		
		$client = $this->createClient();
		
		$this->assertInstanceOf( 'SourceQuery\\Server', $client->server() );
		$this->assertEquals( 1, Connection::$numberOfQueries );
		
		// another request to server should not execute another query
		$this->assertInstanceOf( 'SourceQuery\\Server', $client->server() );
		$this->assertEquals( 1, Connection::$numberOfQueries );
		
		// refetch should execute another query
		$this->assertInstanceOf( 'SourceQuery\\Server', $client->refetchServer() );
		$this->assertEquals( 2, Connection::$numberOfQueries );
		
		// another request to server should not execute another query
		$this->assertInstanceOf( 'SourceQuery\\Server', $client->server() );
		$this->assertEquals( 2, Connection::$numberOfQueries );
	}
	
	/**
	 * Tests client fetch server data
	 */
	public function testChallange()
	{		
		//$client = new Client( '69.162.101.109', 27015 );
		//$client->players();
	}
}