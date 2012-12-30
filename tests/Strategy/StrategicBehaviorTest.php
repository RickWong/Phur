<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
class Phur_Strategy_StrategicBehaviorTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var \Phur\Strategy\StrategicBehavior
	 */
	public $behavior;

	/**
	 * @var \Phur\Strategy\IStrategy
	 */
	public $strategy;

	public function setUp ()
	{
		$this->strategy = Phake::mock('\Phur\Strategy\IStrategy');
		Phake::when($this->strategy)->execute(Phake::anyParameters())->thenReturn('Profit!');

		$this->behavior = new \Phur\Strategy\StrategicBehavior($this->strategy);
	}

	public function testConstructorFailsWithNonIStrategy ()
	{
		$this->setExpectedException('PHPUnit_Framework_Error', 'must implement interface Phur\Strategy\IStrategy');

		new \Phur\Strategy\StrategicBehavior(new stdClass);
	}

	public function testChangeStrategyFailsWithNonIStrategy ()
	{
		$this->setExpectedException('PHPUnit_Framework_Error', 'must implement interface Phur\Strategy\IStrategy');

		$this->behavior->changeStrategy(new stdClass);
	}

	public function testExecuteStrategy ()
	{
		$result = $this->behavior->execute(123, 456);

		Phake::verify($this->strategy, Phake::times(1))->execute(123, 456);

		$this->assertSame('Profit!', $result);
	}
}
