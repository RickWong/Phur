<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
namespace Phur\StateMachine;

class StateMachine
{
	/**
	 * @var string
	 */
	protected $stateInterface = '\Phur\StateMachine\IState';

	/**
	 * @var object
	 */
	protected $currentState;

	/**
	 * @param object $initialState
	 */
	public function __construct ($initialState)
	{
		$this->changeState($initialState);
	}

	/**
	 * @param object $newState
	 *
	 * @return mixed
	 *
	 * @throws \Phur\StateMachine\Exception
	 */
	public function changeState ($newState)
	{
		if (!$newState instanceof $this->stateInterface)
		{
			throw new Exception(get_class($newState)." must implement interface $this->stateInterface!");
		}

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

		if (is_object($result) && $result instanceof $this->stateInterface)
		{
			return $this->changeState($result);
		}

		return $result;
	}
}
