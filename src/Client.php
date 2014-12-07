<?php

namespace SourceQuery;

class Client 
{	
	/**
	 * The connection to the server
	 *
	 * @var SourceQuery\Connection
	 */
	protected $connection = null;
	
	/**
	 * The client configuration
	 *
	 * @var SourceQuery\Configuration
	 */
	public $config = null;
	
	/**
	 * The server object
	 *
	 * @var SourceQuery\Server
	 */
	protected $server = null;
	
	/**
	 * The available server protocols
	 *
	 * @var array[int]
	 */
	private $availableProtocols = [ 109, 73 ];
	
	/**
	 * Create new client object
	 *
	 * @param string 			$ip
	 * @param int				$port
	 * @return void
	 */
	public function __construct( $ip, $port = null, $connect = true, $driver = null ) 
	{
		// assign the configuration
		$this->config = new Configuration;
		
		$this->setIp( $ip );
		$this->setPort( $port );

		// directly try to connect
		$this->connect( $driver );
	}
	
	/**
	 * Set the clients ip 
	 *
	 * @param string		$ip
	 * @return self
	 */
	public function setIp( $ip )
	{
		if ( is_null( $ip ) )
		{
			throw new Exception( "setIp() - first argument cannot be null." );
		}
		
		$this->config->ip = $ip; return $this;
	}
	
	/**
	 * Set the port for the connection. If null is given default port 27015 is used.
	 *
	 * @param int		$port
	 * @return self
	 */
	public function setPort( $port = null )
	{
		if ( is_null( $port ) )
		{
			$port = 27015;
		}
		
		$this->config->port = $port; return $this;
	}
	
	/**
	 * Get the server object or create it
	 *
	 * @return SourceServer
	 */
	public function server()
	{
		if ( !is_null( $this->server ) )
		{
			return $this->server;
		}
		
		if ( !$this->connection->connected() )
		{
			throw new Exception( 'Cannot create server without a connection established.' );
		}
		
		return $this->server = $this->fetchServerObject();
	}
	
	/**
	 * Get the current connection
	 *
	 * @return SoruceQuery\Connection
	 */
	public function connection()
	{
		return $this->connection;
	}
	
	/**
	 * Get the current config
	 *
	 * @return SoruceQuery\Configuration
	 */
	public function config()
	{
		return $this->config;
	}
	
	/**
	 * Create a connection to the source server and close the old connection
	 *
	 * @param string[SourceQuery\Client]			$driver		
	 * @return self
	 */
	public function connect( $driver = null )
	{		
		if ( !is_null( $this->connection ) && $this->connection instanceof Connection )
		{
			$this->connection->disconnect();
		}
		
		if ( is_null( $driver ) )
		{
			$driver = 'SourceQuery\\Connection';
		}
		
		$this->connection = new $driver( $this );
		
		if ( ! $this->connection instanceof Connection )
		{
			throw new Exception( 'Connection driver has to be subclass of SourceQuery\\Connection.' );
		}
		
		return $this;
	}
	
	/**
	 * Get the basic server infos and return them as array
	 *
	 * @return array
	 */
	protected function fetchServerObject()
	{
		$infos = $this->connection()->query( "\xFF\xFF\xFF\xFFTSource Engine Query\x00" );
		
		// Determine the server protocol
		$protocol = hexdec( substr( bin2hex( $infos ), 8, 2 ) );
		
		if ( !in_array( $protocol, $this->availableProtocols ) )
		{
			throw new Exception( 'Unknown server protocol.' );
		}
		
		// create new server object 
		$server = new Server;
		
		// set the protocol
		$server->protocol = $protocol;
		
		list( $infos, $parameters ) = call_user_func( array( $this, 'fetchServerInfosWithProtocol'.$protocol ), $infos );
		
		foreach( $this->formatServerInfos( $infos ) + $this->formatServerParameters( $parameters ) as $key => $value )
		{
			$server->__set( $key, $value );
		}
		
		return $server;
	}
	
	/**
	 * Format the server infos
	 *
	 * @param array 			$infos
	 * @return array
	 */
	private function formatServerInfos( $infos )
	{
		return array_map( function( $item ) 
		{
			return pack( "H*", str_replace( '\\', '', $item ) );
		}, $infos );
	}
	
	/**
	 * Format the server paramters
	 * 
	 * @param array 			$parameters
	 * @return array
	 */
	private function formatServerParameters( $parameters )
	{
		foreach( $parameters as $key => &$value )
		{
			$value = hexdec( $value );
			
			if ( in_array( $key, [ 'environment', 'serverType' ] ) )
			{
				$value = chr( $value );
			}
		}
		
		return $parameters;
	}
	
	/**
	 * fetch server infos with protocol 109 ( Goldsource Games )
	 *
	 * @param string 			$infos
	 * @return SourceQuery\Server
	 */
	private function fetchServerInfosWithProtocol109( $infos )
	{
		$data = [];
		$parameters = [];	
		
		// Split informations
		$infos = chunk_split( substr( bin2hex( $infos ), 10 ), 2, '\\' );
		
		list( $data['ip'], $data['name'], $data['map'], $data['folder'], $data['game'], $data['parameters'] ) = explode( '\\00', $infos );
		
		// Split parameters
		$infos = chunk_split( str_replace('\\', '', substr( $data['parameters'], 0, 18 ) ), 2, ' ' );
		
		list($parameters['playerCount'], $parameters['maxPlayerCount'], $parameters['protocol'], $parameters['serverType'], $parameters['environment'], $parameters['password']) = explode(' ', $infos );
		
		// unset unused parameters
		unset( $data['ip'], $data['parameters'] );
		
		return [ $data, $parameters ];
	}
	
	/**
	 * fetch server infos with protocol 73
	 *
	 * @param string 			$infos
	 * @return SourceQuery\Server
	 */
	private function fetchServerInfosWithProtocol73( $infos )
	{
		$data = [];
		$parameters = [];
		
		$infos = chunk_split(substr(bin2hex($infos), 12), 2, '\\');
		
		// get the main data
		list( $data['name'], $data['map'], $data['folder'], $data['game'], $data['parameters'] ) = explode( '\\00', $infos, 5 );
		
		// get the other parameters
		$infos = explode( ' ', chunk_split( str_replace('\\', '', $data['parameters'] ), 2, ' ' ) );
		
		list( $parameters['id2'], $parameters['id1'], $parameters['playerCount'], $parameters['maxPlayerCount'], $parameters['botsCount'], $parameters['serverType'], $parameters['environment'], $parameters['password']) = $infos;
		
		$parameters['id'] = $parameters['id1'].$parameters['id2']; 
		
		// unset unused parameters
		unset( $parameters['id1'], $parameters['id2'], $data['parameters'] );
		
		return [ $data, $parameters ];
	}


	protected function getChallenge() {
		$challenge = $this->query("\xFF\xFF\xFF\xFFU\xFF\xFF\xFF\xFF");
		return substr($challenge, 5);
	}
	
	// Hex to Signed Dec http://fr2.php.net/manual/en/function.hexdec.php#97172
	private function hexdecs($hex){
	    $dec = hexdec($hex);
	    $max = pow(2, 4 * (strlen($hex) + (strlen($hex) % 2)));
	    $_dec = $max - $dec;
	    return $dec > $_dec ? -$_dec : $dec;
	}	
	public function getPlayers() {
		$challenge = $this->getChallenge();
	
		$infos = $this->query("\xFF\xFF\xFF\xFFU" . $challenge);
		
		$infos = chunk_split(substr(bin2hex($infos), 12), 2, '\\');
		
		$infos = explode('\\', $infos);
		
		$players = array();
		for ($i = 0; isset($infos[$i + 1]); $i = $j + 9) {
			
			// Player name
			$name = '';
			for ($j = $i + 1; isset($infos[$j]) && $infos[$j] != '00'; $j++) $name .= chr(hexdec($infos[$j]));
			
			if (!isset($infos[$j + 8])) break;
			
			// Gametime
			eval('$time="\x'.trim(chunk_split($infos[$j + 5] . $infos[$j + 6] . $infos[$j + 7] . $infos[$j + 8], 2,"\x"), "\x") . '";');
			list(,$time) = unpack('f', $time);
			
			// Score
			$score = ltrim($infos[$j + 4] . $infos[$j + 3] . $infos[$j + 2] . $infos[$j + 1], '0');
			
			$players[] = array(
				'id'	=>	hexdec($infos[$i]),
				'name'	=>	$name,
				'score'	=>	empty($score)? 0 : $this->hexdecs($score),
				'time'	=>	$time
			);
		}
		return $players;
	}
}
?>