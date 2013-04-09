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
	protected $moreSpecificInterface = '\Phur\StateMachine\IState';

	/**
	 * @var IState
	 */
	protected $currentState;

	/**
	 * @param IState $initialState
	 */
	public function __construct (IState $initialState)
	{
		$this->changeState($initialState);
	}

	/**
	 * @param IState $newState
	 *
	 * @return mixed
	 *
	 * @throws \Phur\StateMachine\Exception
	 */
	public function changeState (IState $newState)
	{
		if (!$this->isValidState($newState))
		{
			throw new Exception(get_class($newState)." must implement interface $this->moreSpecificInterface!");
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

		if ($this->isValidState($result))
		{
			return $this->changeState($result);
		}

		return $result;
	}

	/**
	 * @param mixed $state
	 *
	 * @return bool
	 */
	public function isValidState ($state)
	{
		return is_object($state) && $state instanceof $this->moreSpecificInterface;
	}
}
