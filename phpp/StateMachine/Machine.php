<?php
namespace phpp\StateMachine;

class Machine 
{
	/**
	 * @var IState
	 */
	private $currentState;

	/**
	 * @var IState $initialState
	 */
	public function __construct (IState $initialState)
	{
		$this->changeState($initialState);
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
			$this->currentState->onExit();
		}

		$this->currentState = $newState;

		return $this->currentState->onEnter();
	}

	/**
	 * @return mixed
	 * 
	 * @throws \phpp\StateMachine\Exception
	 */
	public function execute ()
	{
		if (!$this->currentState) 
		{
			throw new Exception("No current state to execute!");
		}

		return $this->currentState->onExecute($this);
	}
}
