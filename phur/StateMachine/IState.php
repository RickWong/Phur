<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
namespace Phur\StateMachine;

interface IState 
{
	/**
	 * @return mixed
	 */
	public function before ();

	/**
	 * @param StateMachine $runningMachine
	 * 
	 * @return mixed
	 */
	public function execute (StateMachine $runningMachine);

	/**
	 * @return void
	 */
	public function after ();

	/**
	 * Sleep
	 */
	public function __sleep ();

	/**
	 * Wake
	 */
	public function __wake ();
}
