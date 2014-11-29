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
	public function connect( Client $client )
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
		switch ( $query ) 
		{
			case "\xFF\xFF\xFF\xFFTSource Engine Query\x00":
			
			$responseString = "????I|POG| WWII Occupation Roleplay (Revision 2688 | Server 1 | Peop\000rp_kielce_pog_v42u7\000garrysmod\0001942RP\000?QZ\000dw\00014.07.10\000??iH?@ gm:1942rp\000?\000\000\000\000\000\000";
			
			break;
		}

		return $responseString;
	}
}