<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
class Phur_Factory_FactoryTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var \Phur\AbstractFactory\Factory
	 */
	public $factory;

	public function setUp ()
	{
		$this->factory = Phake::partialMock('\Phur\AbstractFactory\Factory', 'default');
	}

	public function testCreateFailsWithNonIProduct ()
	{
		$this->setExpectedException('\Phur\AbstractFactory\Exception', 'must implement interface \Phur\AbstractFactory\IProduct');

		$this->factory->create('stdClass');
	}

	public function testCreateWorksWithDefaultArguments ()
	{
		$result = $this->factory->create('Phur_Factory_TestProduct');

		Phake::verify($this->factory)->newInstance('Phur_Factory_TestProduct', array('default'));

		$this->assertInstanceOf('Phur_Factory_TestProduct', $result);
	}

	public function testCreateWorksWithAdditionalConstructorArguments ()
	{
		$result = $this->factory->create('Phur_Factory_TestProduct', 'custom');

		Phake::verify($this->factory)->newInstance('Phur_Factory_TestProduct', array('default', 'custom'));

		$this->assertInstanceOf('Phur_Factory_TestProduct', $result);
	}
}

class Phur_Factory_TestProduct implements \Phur\AbstractFactory\IProduct
{
	public function __construct() {}
}
