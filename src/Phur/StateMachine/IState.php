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
	 * @return mixed|IState Returns result of state, or returns a new IState to change to
	 */
	public function execute ();

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
