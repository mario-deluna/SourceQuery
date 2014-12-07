<?php 

namespace SourceQuery\Tests;

use SourceQuery\Client;
use SourceQuery\Test\Connection;

class ServerTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Create new client objcet with test connection
	 */
	public function createServer()
	{
		$client = new Client( '1.2.3.4', 27015, true, 'SourceQuery\\Test\\Connection' );
		return $client->server();
	}
	
	/**
	 * Tests server object data
	 */
	public function testObjectData()
	{
		$server = $this->createServer();
		
		$this->assertEquals( 73, $server->protocol );
		$this->assertEquals( 'ClanCats Source-TestServer', $server->name );
		$this->assertEquals( 'gm_ccflatgrass', $server->map );
		$this->assertEquals( 'garrysmod', $server->folder );
		$this->assertEquals( 'GMod Sandbox', $server->game );
		$this->assertEquals( 4000, $server->id );
		$this->assertEquals( 40, $server->playerCount );
		$this->assertEquals( 100, $server->maxPlayerCount );
		$this->assertEquals( 0, $server->botsCount );
		$this->assertEquals( 'd', $server->serverType );
		$this->assertEquals( 'w', $server->environment );
		$this->assertEquals( false, $server->password );
		$this->assertEquals( true, $server->vac );
	}
	
	/**
	 * Tests server object data
	 */
	public function testServerTypeFull()
	{
		$server = $this->createServer();
		$this->assertEquals( 'dedicated server', $server->serverTypeFull );
		
		$server->serverType = 'l';
		$this->assertEquals( 'non-dedicated server', $server->serverTypeFull );
		
		$server->serverType = 'p';
		$this->assertEquals( 'SourceTV relay (proxy)', $server->serverTypeFull );
		
		$server->serverType = 'bla';
		$this->assertEquals( 'Unknown Server Type', $server->serverTypeFull );
	}
}