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
		$result = $this->currentState->execute();

		if (is_object($result) && class_implements($result, '\Phur\StateMachine\IState'))
		{
			return $this->changeState($result);
		}

		return $result;
	}
}
