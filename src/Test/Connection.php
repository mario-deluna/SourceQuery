<?php 

namespace SourceQuery\Test;

class Connection extends \SourceQuery\Connection
{	
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

		return $responseString;
	}
}