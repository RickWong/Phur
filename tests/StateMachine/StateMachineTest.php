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

		$this->stateMachine = new \Phur\StateMachine\StateMachine;
		$this->stateMachine->changeState($this->state);
	}

	public function testChangeStateOnlyAcceptsSpecificInterface ()
	{
		$this->setExpectedException('\Phur\StateMachine\Exception', 'must implement interface ISpecificInterface');

		$specificMachine = new \Phur\StateMachine\StateMachine('ISpecificInterface');
		$specificMachine->changeState($this->state);
	}

	public function testChangeStateWorks ()
	{
		$new_state = Phake::mock('\Phur\StateMachine\IState');
		Phake::when($new_state)->before(Phake::anyParameters())->thenReturn('New state!');

		$result = $this->stateMachine->changeState($new_state);

		Phake::verify($this->state)->after();
		Phake::verify($new_state)->before();

		$this->assertSame('New state!', $result);
	}

	public function testExecuteFailsWithoutState ()
	{
		$this->setExpectedException('\Phur\StateMachine\Exception', 'Cannot execute a stateless state machine!');

		$emptyMachine = new \Phur\StateMachine\StateMachine;
		$emptyMachine->execute();
	}

	public function testExecuteWorks ()
	{
		Phake::when($this->state)->execute(Phake::anyParameters())->thenReturn('United state!');

		$result = $this->stateMachine->execute();

		Phake::verify($this->state)->execute();

		$this->assertSame('United state!', $result);
	}

	public function testExecuteWorksAndChangesState ()
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
