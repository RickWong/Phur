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
	protected $stateInterface;

	/**
	 * @var IState
	 */
	protected $currentState;

	/**
	 * @var string $stateInterface
	 */
	public function __construct ($stateInterface = '\Phur\StateMachine\IState')
	{
		$this->stateInterface = $stateInterface;
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
		if (!$this->_isState($newState))
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
	 *
	 * @throws \Phur\StateMachine\Exception
	 */
	public function execute ()
	{
		if (!$this->currentState)
		{
			throw new Exception('Cannot execute a stateless state machine!');
		}

		$result = $this->currentState->execute();

		if ($this->_isState($result))
		{
			return $this->changeState($result);
		}

		return $result;
	}

	/**
	 * @param mixed $object
	 *
	 * @return bool
	 */
	protected function _isState ($object)
	{
		return is_subclass_of($object, $this->stateInterface, FALSE);
	}
}
