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

	public function testChangeStateFailsWithNonIState ()
	{
		$this->setExpectedException('\Phur\StateMachine\Exception', 'must implement interface \Phur\StateMachine\IState!');

		$phakeStateMachine = Phake::mock('\Phur\StateMachine\StateMachine');
		Phake::when($phakeStateMachine)->isValidState->thenReturn(FALSE);
		Phake::when($phakeStateMachine)->changeState->thenCallParent();

		$phakeStateMachine->changeState($this->state);
	}

	public function testChangeStateWorks ()
	{
		$new_state = Phake::mock('\Phur\StateMachine\IState');
		Phake::when($new_state)->before->thenReturn('New state!');

		$result = $this->stateMachine->changeState($new_state);

		Phake::verify($this->state)->after();
		Phake::verify($new_state)->before();

		$this->assertSame('New state!', $result);
	}

	public function testExecuteWorks ()
	{
		Phake::when($this->state)->execute->thenReturn('United state!');

		$result = $this->stateMachine->execute();

		Phake::verify($this->state)->execute();

		$this->assertSame('United state!', $result);
	}

	public function testExecuteWorksAndChangesState ()
	{
		$new_state = Phake::mock('\Phur\StateMachine\IState');
		Phake::when($new_state)->before->thenReturn('New state!');
		Phake::when($this->state)->execute->thenReturn($new_state);

		$result = $this->stateMachine->execute();

		Phake::verify($this->state)->execute();
		Phake::verify($new_state)->before();

		$this->assertSame('New state!', $result);
	}
}
