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

	public function testChangeStrategyFailsWithNonIStrategy ()
	{
		$this->setExpectedException('\Phur\Strategy\Exception', 'must implement interface \Phur\Strategy\IStrategy!');

		$this->behavior->changeStrategy(new stdClass);
	}

	public function testExecuteStrategy ()
	{
		$result = $this->behavior->execute(123, 456);

		Phake::verify($this->strategy)->execute(123, 456);

		$this->assertSame('Profit!', $result);
	}
}
