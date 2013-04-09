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
		$this->factory = Phake::partialMock('\Phur\AbstractFactory\Factory', array('default'));
	}

	public function testCreateFailsIfClassDoesNotImplementIProduct ()
	{
		$this->setExpectedException('\Phur\AbstractFactory\Exception', 'must implement interface \Phur\AbstractFactory\IProduct');

		$this->factory->create('stdClass');
	}

	public function testCreateWorksWithDefaultArguments ()
	{
		$result = $this->factory->create('Phur_Factory_Product');

		$this->assertInstanceOf('Phur_Factory_Product', $result);

        Phake::verify($this->factory)->newInstance('Phur_Factory_Product', array('default'));
	}

	public function testCreateWorksWithAdditionalConstructorArguments ()
	{
		$result = $this->factory->create('Phur_Factory_Product', array('custom'));

		$this->assertInstanceOf('Phur_Factory_Product', $result);

        Phake::verify($this->factory)->newInstance('Phur_Factory_Product', array('default', 'custom'));
	}
}

class Phur_Factory_Product implements \Phur\AbstractFactory\IProduct
{
	public function __construct () {}
}
