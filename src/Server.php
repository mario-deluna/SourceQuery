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
		/*
		 * byte 	Protocol version used by the server.
		 */
		'protocol'			=> 0,
		
		/*
		 * string	Name of the server.
		 */
		'name'				=> '',
		
		/*
		 * string	Map the server has currently loaded.
		 */
		'map'				=> '',
		
		/*
		 * string	Name of the folder containing the game files.
		 */
		'folder' 			=> '',
		
		/*
		 * string	Full name of the game.
		 *
		 * This can also be the name of the gamemode
		 */
		'game'				=> '',
		
		/*
		 * short	Steam Application ID of game.
		 */
		'id'				=> 0,
		
		/*
		 * int		Number of players on the server.
		 */
		'playerCount'		=> 0,
		
		/*
		 * int		Maximum number of players the server reports it can hold.
		 */
		'maxPlayerCount'	=> 0,
		
		/*
		 * int		Number of bots on the server.
		 */
		'botsCount'			=> 0,
		
		/*
		 * string 	Indicates the type of server:
		 *  'd' for a dedicated server
		 *  'l' for a non-dedicated server
		 *  'p' for a SourceTV relay (proxy)
		 */
		'serverType'		=> '',
		
		/*
		 * string	Indicates the operating system of the server:
		 *  'l' for Linux
		 *  'w' for Windows
		 *  'm' or 'o' for Mac (the code changed after L4D1)
		 */
		'environment'		=> '',
		
		/*
		 * bool 	Indicates whether the server requires a password:
		 *  false for public
		 *  true for private
		 */
		'password' 			=> false,
		
		/*
		 * bool 	Specifies whether the server uses VAC:
		 *  false for unsecured
		 *  true for secured
		 */
		'vac'				=> false
	];
	
	/**
	 * Convert password to bool 
	 *
	 * @param mixed 		$password
	 * @return bool
	 */
	protected function setModifierPassword( $password )
	{
		return (bool) $password;
	}
}