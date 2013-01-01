<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
class Phur_StateMachine_StateMachineTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var \Phur\StateMachine\StateMachine
	 */
	public $stateMachine;

	/**
	 * @var \Phur\StateMachine\IState
	 */
	public $state;

	public function setUp ()
	{
		$this->state = Phake::mock('\Phur\StateMachine\IState');

		$this->stateMachine = new \Phur\StateMachine\StateMachine($this->state);
	}

	public function testConstructorFailsWithNonIState ()
	{
		$this->setExpectedException('PHPUnit_Framework_Error', 'must implement interface Phur\StateMachine\IState');

		new \Phur\StateMachine\StateMachine(new stdClass);
	}

	public function testChangeStateFailsWithNonIState ()
	{
		$this->setExpectedException('PHPUnit_Framework_Error', 'must implement interface Phur\StateMachine\IState');

		$this->stateMachine->changeState(new stdClass);
	}

	public function testChangeStateCallsCurrentAfterAndNewBefore ()
	{
		$new_state = Phake::mock('\Phur\StateMachine\IState');
		Phake::when($new_state)->before(Phake::anyParameters())->thenReturn('New state!');

		$result = $this->stateMachine->changeState($new_state);

		Phake::verify($this->state)->after();
		Phake::verify($new_state)->before();

		$this->assertSame('New state!', $result);
	}

	public function testExecuteState ()
	{
		Phake::when($this->state)->execute(Phake::anyParameters())->thenReturn('United state!');
		$result = $this->stateMachine->execute();

		Phake::verify($this->state)->execute();

		$this->assertSame('United state!', $result);
	}

	public function testExecuteStateReceivesNewStateAndChangesToIt ()
	{
		$new_state = Phake::mock('\Phur\StateMachine\IState');
		Phake::when($new_state)->before(Phake::anyParameters())->thenReturn('New state!');
		Phake::when($this->state)->execute(Phake::anyParameters())->thenReturn($new_state);

		$result = $this->stateMachine->execute();

		Phake::verify($this->state)->execute();
		Phake::verify($new_state)->before();

		$this->assertSame('New state!', $result);
	}
}
