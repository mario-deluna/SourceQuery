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


How to use
----------

### Example

PHP:

	require_once 'lib/SourceQuery.php';
	
	$server = new SourceQuery('217.70.184.250', 27015);
	$infos  = $server->getInfos();
	echo 'There is ' . $infos['players'] . ' player(s) on the server "' .$infos['name'] . '".';