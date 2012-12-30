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
	 * @var IState $initialState
	 */
	public function __construct (IState $initialState)
	{
		$this->changeState($initialState);
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
	 */
	public function execute ()
	{
		return $this->currentState->execute($this);
	}
}
