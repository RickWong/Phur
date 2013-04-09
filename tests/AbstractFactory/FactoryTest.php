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
		$this->factory = Phake::partialMock('Phone_Factory', array('touchscreen'));
	}

    public function testCreateFailsIfClassDoesNotImplementIProduct ()
    {
        $this->setExpectedException('\Phur\AbstractFactory\Exception', 'must implement interface IPhone');

        $this->factory->create('stdClass');
    }

    public function testCreateFailsIfClassLacksConstructorButDependencyGiven ()
    {
        $this->setExpectedException('\Phur\AbstractFactory\Exception', 'constructor does not accept dependencies');

        $this->factory->create('Dumb_Phone');
    }

    public function testCreateWorksWithDependency ()
	{
		$result = $this->factory->create('Smart_Phone');

		$this->assertInstanceOf('Smart_Phone', $result);

        Phake::verify($this->factory)->newInstance('Smart_Phone', array('touchscreen'));
	}

	public function testCreateWorksWithAdditionalDependency ()
	{
		$result = $this->factory->create('Smart_Phone', array('wifi'));

		$this->assertInstanceOf('Smart_Phone', $result);

        Phake::verify($this->factory)->newInstance('Smart_Phone', array('touchscreen', 'wifi'));
	}
}

class Phone_Factory extends \Phur\AbstractFactory\Factory
{
    protected $productInterface = 'IPhone';
}

interface IPhone
{
}

class Smart_Phone implements IPhone
{
    public function __construct ($dependencies)
    {
    }
}

class Dumb_Phone implements IPhone
{
}
