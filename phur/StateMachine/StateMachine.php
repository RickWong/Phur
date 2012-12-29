<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
namespace Phur\StateMachine;

class StateMachine
{
	/**
	 * @var IState
	 */
	private $currentState;

	/**
	 * @var IState $initialState (Optional)
	 */
	public function __construct (IState $initialState = NULL)
	{
		if ($initialState)
		{
			$this->changeState($initialState);
		}
	}

	/**
	 * @return IState
	 */
	public function getState ()
	{
		return $this->currentState;
	}

	/**
	 * @param IState $newState
	 * 
	 * @return mixed
	 */
	public function changeState (IState $newState)
	{
		if ($this->currentState)
		{
			$this->currentState->after();
		}

		$this->currentState = $newState;

		return $this->currentState->before();
	}

	/**
	 * @return mixed
	 * 
	 * @throws \Phur\StateMachine\Exception
	 */
	public function execute ()
	{
		if (!$this->currentState) 
		{
			throw new Exception("No current state to execute!");
		}

		return $this->currentState->execute($this);
	}
}
