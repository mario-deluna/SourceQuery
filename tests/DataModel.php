<?php 

namespace SourceQuery\Tests;

use SourceQuery\DataModel;

class DataModelTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test datamodel construct
	 */
	public function testConstruct()
	{
		$this->assertInstanceOf( 'SourceQuery\DataModel', new DataModel );
	}
	
	/**
	 * Test datamodel isset
	 */
	public function testIsset()
	{
		$model = new DataModel;
		
		$model->foo = 'bar';
		
		$this->assertTrue( isset( $model->foo ) );
		$this->assertFalse( isset( $model->bar ) );
	}
	
	/**
	 * Test datamodel isset
	 */
	public function testUnset()
	{
		$model = new DataModel;
		
		$model->foo = 'bar';
		
		$this->assertTrue( isset( $model->foo ) );
		
		unset( $model->foo );
		
		$this->assertFalse( isset( $model->foo ) );
	}
	
	/**
	 * Test datamodel json
	 */
	public function testJson()
	{
		$model = new DataModel;
		
		$model->foo = 'bar';
		$model->bar = 'foo';
		
		$this->assertEquals( '{"foo":"bar","bar":"foo"}', $model->json() );
	}
}