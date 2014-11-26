<?php 

namespace SourceQuery;

class Connection
{	
	/**
	 * The socket connection
	 *
	 * @var resource
	 */
	protected $connection = null; 
	
	/**
	 * Create new Connection object
	 *
	 * @param Client 			$client
	 * @return void
	 */
	public function __construct( Client $client )
	{
		$this->connect( $client );
	}
	
	/**
	 * Start a new socket connection
	 *
	 * @param Client 			$client
	 * @return void
	 */
	public function connect( Client $client )
	{
		$ip = $client->config->ip;
		$port = $client->config->port;
		
		$this->connection = fsockopen( 'udp://' . $ip, $port, $errno, $errstr, $client->config->timeout );
		
		if ( !$this->connection )
		{
			throw new Exception( 'Connection to: '.$ip.':'.$port.' failed. '.$errno.' - '.$errstr );
		}
		
		// also set the timeout for next connection
		stream_set_timeout( $this->connection, $client->config->timeout );
	}
	
	/**
	 * End the socket connection
	 *
	 * @return void
	 */
	public function disconnect()
	{
		if ( !is_null( $this->connection ) )
		{
			fclose( $this->connection ); $this->connection = null;
		}
	}
}