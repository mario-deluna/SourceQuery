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
	public function __construct( $ip, $port = null ) 
	{
		// assign the configuration
		$this->config = new Configuration;
		
		$this->setIp( $ip );
		$this->setPort( $port );

		// directly try to connect
		$this->connect();
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
	 * Create a connection to the source server and close the old connection
	 *
	 * @return self
	 */
	public function connect()
	{
		if ( !is_null( $this->connection ) && $this->connection instanceof Connection )
		{
			$this->connection->disconnect();
		}
		
		$this->connection = new Connection( $this );
		
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
		
		$infos = call_user_func( array( $this, 'fetchServerInfosWithProtocol'.$protocol ), $infos );
	}
	
	private function fetchServerInfosWithProtocol109( $infos )
	{var_dump( $infos ); die;
		// Split informations
		$infos = chunk_split(substr(bin2hex($infos), 10), 2, '\\');
		
		
		
		list($serveur['ip'], $serveur['name'], $serveur['map'], $serveur['mod'], $serveur['modname'], $serveur['params']) = explode('\\00', $infos);
		
		// Split parameters
		$serveur['params'] = substr($serveur['params'],0,18);
		
		$serveur['params'] = chunk_split(str_replace('\\', '', $serveur['params']), 2, ' ');
		list($params['players'], $params['places'], $params['protocol'], $params['dedie'], $params['os'], $params['pass']) = explode(' ', $serveur['params']);
		$params = array(
			'id'		=>	0, // Unsupported
			'bots'		=>	0, // Unsupported
			'ip'		=>	$this->ip,
			'port'		=>	$this->port,
			'players'	=>	hexdec($params['players']),
			'places'	=>	hexdec($params['places']),
			'protocol'	=>	hexdec($params['protocol']),
			'dedie'		=>	chr(hexdec($params['dedie'])),
			'os'		=>	chr(hexdec($params['os'])),
			'pass'		=>	hexdec($params['pass'])
		);
		unset($serveur['ip']);
		unset($serveur['params']);
		
		$serveur = array_map(function($item){
			return pack("H*", str_replace('\\', '', $item));
		}, $serveur);
		
		$infos = ($params + $serveur);
	
		var_dump($infos); die;
	}
	
	private function fetchServerInfosWithProtocol73( $infos )
	{
		$server = new Server();
		
		$infos = chunk_split(substr(bin2hex($infos), 12), 2, '\\');
		
		list( $server->name, $server->map, $server->folder, $server->game, $serveur['params']) = explode('\\00', $infos, 5);
		
		// Split parameters
		$serveur['params'] = substr($serveur['params'], 0);
		
		$serveur['params'] = chunk_split(str_replace('\\', '', $serveur['params']), 2, ' ');
		list($params['id1'], $params['id2'], $params['players'], $params['places'], $params['bots'], $params['dedie'], $params['os'], $params['pass']) = explode(' ', $serveur['params']);
		$params=array(
			'id'		=>  hexdec($params['id2'] . $params['id1']),
			'ip'		=>	$this->ip,
			'port'		=>	$this->port,
			'players'	=>	hexdec($params['players']),
			'places'	=>	hexdec($params['places']),
			'bots'		=>	hexdec($params['bots']),
			'protocol'	=>	73,
			'dedie'		=>	chr(hexdec($params['dedie'])),
			'os'		=>	chr(hexdec($params['os'])),
			'pass'		=>	hexdec($params['pass'])
		);
		unset($serveur['params']);
		
		$serveur = array_map(function($item){
			return pack("H*", str_replace('\\', '', $item));
		}, $serveur);
		
		$infos = ($serveur + $params);
		
		var_dump($infos); die;
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
	
	public function getInfos() {
		$infos = $this->query("\xFF\xFF\xFF\xFFTSource Engine Query\x00");

		//  Détermine le protocole utilisé
		$protocol = hexdec(substr(bin2hex($infos), 8, 2));
			
		if($protocol == 109) return $this->getInfos1($infos);
		else if($protocol == 73) return $this->getInfos2($infos);
		
		trigger_error('Unknown server type', E_USER_NOTICE);
		return false;
	}
	
	protected function getInfos1($infos) {
		// Split informations
		$infos = chunk_split(substr(bin2hex($infos), 10), 2, '\\');
		@list($serveur['ip'], $serveur['name'], $serveur['map'], $serveur['mod'], $serveur['modname'], $serveur['params']) = explode('\\00', $infos);
		
		// Split parameters
		$serveur['params'] = substr($serveur['params'],0,18);
		
		$serveur['params'] = chunk_split(str_replace('\\', '', $serveur['params']), 2, ' ');
		list($params['players'], $params['places'], $params['protocol'], $params['dedie'], $params['os'], $params['pass']) = explode(' ', $serveur['params']);
		$params = array(
			'id'		=>	0, // Unsupported
			'bots'		=>	0, // Unsupported
			'ip'		=>	$this->ip,
			'port'		=>	$this->port,
			'players'	=>	hexdec($params['players']),
			'places'	=>	hexdec($params['places']),
			'protocol'	=>	hexdec($params['protocol']),
			'dedie'		=>	chr(hexdec($params['dedie'])),
			'os'		=>	chr(hexdec($params['os'])),
			'pass'		=>	hexdec($params['pass'])
		);
		unset($serveur['ip']);
		unset($serveur['params']);
		
		$serveur = array_map(function($item){
			return pack("H*", str_replace('\\', '', $item));
		}, $serveur);
		
		$infos = ($params + $serveur);
		return $infos;
	}
	
	protected function getInfos2($infos) {
		// Split informations
		$infos = chunk_split(substr(bin2hex($infos), 12), 2, '\\');
		@list($serveur['name'], $serveur['map'], $serveur['mod'], $serveur['modname'], $serveur['params']) = explode('\\00', $infos, 5);
		
		// Split parameters
		$serveur['params'] = substr($serveur['params'], 0);
		
		$serveur['params'] = chunk_split(str_replace('\\', '', $serveur['params']), 2, ' ');
		list($params['id1'], $params['id2'], $params['players'], $params['places'], $params['bots'], $params['dedie'], $params['os'], $params['pass']) = explode(' ', $serveur['params']);
		$params=array(
			'id'		=>  hexdec($params['id2'] . $params['id1']),
			'ip'		=>	$this->ip,
			'port'		=>	$this->port,
			'players'	=>	hexdec($params['players']),
			'places'	=>	hexdec($params['places']),
			'bots'		=>	hexdec($params['bots']),
			'protocol'	=>	73,
			'dedie'		=>	chr(hexdec($params['dedie'])),
			'os'		=>	chr(hexdec($params['os'])),
			'pass'		=>	hexdec($params['pass'])
		);
		unset($serveur['params']);
		
		$serveur = array_map(function($item){
			return pack("H*", str_replace('\\', '', $item));
		}, $serveur);
		
		$infos = ($serveur + $params);	
		return $infos;
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