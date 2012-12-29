<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
namespace phpp\StateMachine;

interface IState 
{
	/**
	 * @return mixed
	 */
	public function onEnter ();

	/**
	 * @param StateMachine $runningMachine
	 * 
	 * @return mixed
	 */
	public function onExecute (StateMachine $runningMachine);

	/**
	 * @return void
	 */
	public function onExit ();

	/**
	 * Sleep
	 */
	public function __sleep ();

	/**
	 * Wake
	 */
	public function __wake ();
}
