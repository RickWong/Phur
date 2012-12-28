<?php
namespace phpp\StateMachine;

interface IState 
{
	/**
	 * @return mixed
	 */
	public function onEnter ();

	/**
	 * @param Machine $runningMachine
	 * 
	 * @return mixed
	 */
	public function onExecute (Machine $runningMachine);

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
