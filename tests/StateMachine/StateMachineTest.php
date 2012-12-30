<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
class Phur_StateMachine_StateMachineTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var \Phur\StateMachine\StateMachine
	 */
	public $statemachine;

	/**
	 * @var \Phur\StateMachine\IState
	 */
	public $state;

	public function setUp ()
	{
		$this->state = Phake::mock('\Phur\StateMachine\IState');
		Phake::when($this->state)->execute(Phake::anyParameters())->thenReturn('Happy state');

		$this->statemachine = new \Phur\StateMachine\StateMachine($this->state);
	}

	public function testConstructorFailsWithNonIState ()
	{
		$this->setExpectedException('PHPUnit_Framework_Error', 'must implement interface Phur\StateMachine\IState');

		new \Phur\StateMachine\StateMachine(new stdClass);
	}

	public function testChangeStateFailsWithNonIState ()
	{
		$this->setExpectedException('PHPUnit_Framework_Error', 'must implement interface Phur\StateMachine\IState');

		$this->statemachine->changeState(new stdClass);
	}

	public function testExecuteState ()
	{
		$result = $this->statemachine->execute();

		Phake::verify($this->state, Phake::times(1))->execute($this->statemachine);

		$this->assertSame('Happy state', $result);
	}
}
