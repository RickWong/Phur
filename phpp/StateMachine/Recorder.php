<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
namespace phpp\StateMachine;

abstract class Recorder 
{
	/**
	 * @var IState
	 */
	protected $state;

	/**
	 * @param IState $state
	 */
	public function __construct (IState $state)
	{
		$this->state = serialize($state);
	}

	/**
	 * @return bool
	 */
	abstract public function save ();

	/**
	 * @return IState
	 */
	abstract public function load ();
}
