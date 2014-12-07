<?php 

namespace SourceQuery\Test;

class Connection extends \SourceQuery\Connection
{	
	/**
	 * Count the number of times a conncection has been etablished.
	 *
	 * @var int
	 */
	public static $numberOfConnections = 0;
	
	/**
	 * Count the number of queries executed
	 *
	 * @var int
	 */
	public static $numberOfQueries = 0;
	
	/**
	 * The current connection status
	 *
	 * @var bool
	 */
	protected $connectionStatus = false;
	
	/**
	 * Is the connection established
	 *
	 * @return bool
	 */
	public function connected()
	{
		return $this->connectionStatus;
	}

	/**
	 * Start a new socket connection
	 *
	 * @param Client 			$client
	 * @return void
	 */
	public function connect( \SourceQuery\Client $client )
	{
		static::$numberOfConnections++;
		$this->connectionStatus = true;
	}

	/**
	 * End the socket connection
	 *
	 * @return void
	 */
	public function disconnect()
	{
		$this->connectionStatus = false;
	}

	/**
	 * Query the soruce server
	 *
	 * @param string			$query
	 */
	public function query( $query )
	{
		$queriesPath = __DIR__.'/../../tests/queries/';
		
		switch ( $query ) 
		{
			case "\xFF\xFF\xFF\xFFTSource Engine Query\x00":
			
			$responseString = file_get_contents( $queriesPath.'fetchServerData' );
			
			break;
		}
		
		static::$numberOfQueries++;
		
		return $responseString;
	}
}