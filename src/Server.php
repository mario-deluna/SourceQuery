<?php 

namespace SourceQuery;

class Server extends DataModel
{
	/**
	 * The current data holder
	 *
	 * @see https://developer.valvesoftware.com/wiki/Server_queries#Response_Format
	 * 
	 * @var array
	 */
	protected $_model_data = 
	[
		'protocol'			=> 0,
		'name'				=> '',
		'map'				=> '',
		'folder' 			=> '',
		'game'				=> '',
		'id'				=> 0,
		'playerCount'		=> 0,
		'maxPlayerCount'	=> 0,
		'botsCount'			=> 0,
		'serverType'		=> '',
		'environment'		=> '',
		'visibility' 		=> false,
		'vac'				=> false,
		'ip'				=> null,
		'port'				=> 0,
	];
}