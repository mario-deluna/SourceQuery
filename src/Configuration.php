<?php 

namespace SourceQuery;

class Configuration extends DataModel
{
	/**
	 * The default configuration 
	 *
	 * @var array
	 */
	protected $default_configuration_values = 
	[
		// the socket timeout
		'timeout' => 3,
	];

	/**
	 * Create new Configuration instance
	 *
	 * @param array  		$conf
	 * @return void
	 */
	public function __construct( $conf = [] )
	{
		$this->_model_data = $this->default_configuration_values;
		
		foreach( $conf as $key => $value )
		{
			$this->__set( $key, $value );
		}
	}
}