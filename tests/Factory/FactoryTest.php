<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
class Phur_Factory_FactoryTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var \Phur\Factory\Factory
	 */
	public $factory;

	public function setUp ()
	{
		$this->factory = Phake::partialMock('\Phur\Factory\Factory', NULL, array('default'));
	}

	public function testCreateFailsWithNonIProduct ()
	{
		$this->setExpectedException('\Phur\Factory\Exception', 'must implement interface \Phur\Factory\IProduct');

		$this->factory->create('stdClass');
	}

	public function testCreateWorks ()
	{
		$result = $this->factory->create('Phur_Factory_TestProduct');

		Phake::verify($this->factory)->_newInstance('Phur_Factory_TestProduct', array('default'));

		$this->assertInstanceOf('Phur_Factory_TestProduct', $result);
	}

	public function testCreateWorksWithCustomConstructorArgumentsAppended ()
	{
		$result = $this->factory->create('Phur_Factory_TestProduct', array('custom'));

		Phake::verify($this->factory)->_newInstance('Phur_Factory_TestProduct', array('default', 'custom'));

		$this->assertInstanceOf('Phur_Factory_TestProduct', $result);
	}

	public function testCreateWorksWithCustomConstructorArgumentsInstead ()
	{
		$result = $this->factory->create('Phur_Factory_TestProduct', array('custom'), FALSE);

		Phake::verify($this->factory)->_newInstance('Phur_Factory_TestProduct', array('custom'));

		$this->assertInstanceOf('Phur_Factory_TestProduct', $result);
	}
}

class Phur_Factory_TestProduct implements \Phur\Factory\IProduct
{
	public function __construct() {}
}
