SourceQuery
===========

[![Build Status](https://travis-ci.org/mario-deluna/SourceQuery.svg)](https://travis-ci.org/mario-deluna/SourceQuery)

This is a fork of the original [SourceQuery by Yannickcr](https://github.com/yannickcr/SourceQuery).

This little PHP library helps to query a [Source engine](http://en.wikipedia.org/wiki/Source_%28game_engine%29) server for games like:

 * Counter Strike Source
 * Team Fortress
 * Left 4 Dead
 * Garrys Mod
 * and many more..


## Installation

Simply add the mario-deluna/sourcequery to you composer requirements.

```json
"require": 
{
	"mario-deluna/sourcequery": "dev-master"
}
```


## How to use

### Example

```php
$client = new SourceQuery\Client( '127.0.0.1', 27015 );

// print the name of the server
echo $client->server()->name;
```

### The server object

```php
$server = $client->server();

/*
 * byte 	Protocol version used by the server.
 */
$server->protocol;

/*
 * string	Name of the server.
 */
$server->name;

/*
 * string	Map the server has currently loaded.
 */
$server->map;

/*
 * string	Name of the folder containing the game files.
 */
$server->folder;

/*
 * string	Full name of the game.
 * Don't get confused this is the name of the gamemode.
 */
$server->game;

/*
 * short	Steam Application ID of game.
 */
$server->id;

/*
 * int		Number of players on the server.
 */
$server->playerCount;

/*
 * int		Maximum number of players the server reports it can hold.
 */
$server->maxPlayerCount;

/*
 * int		Number of bots on the server.
 */
$server->botsCount;

/*
 * string 	Indicates the type of server:
 *  'd' for a dedicated server
 *  'l' for a non-dedicated server
 *  'p' for a SourceTV relay (proxy)
 */
$server->serverType;

/*
 * string 	Same as server type but return the full string
 */
$server->serverTypeFull;

/*
 * string	Indicates the operating system of the server:
 *  'l' for Linux
 *  'w' for Windows
 *  'm' or 'o' for Mac (the code changed after L4D1)
 */
$server->environment;


/*
 * bool 	Indicates whether the server requires a password:
 *  false for public
 *  true for private
 */
$server->password;

/*
 * bool 	Specifies whether the server uses VAC:
 *  false for unsecured
 *  true for secured
 */
$server->vac;
```

## Notes:

* For more informations, please read the [Server queries documentation](http://developer.valvesoftware.com/wiki/Server_queries)