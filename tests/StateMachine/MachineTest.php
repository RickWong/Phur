<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 * @package phpp
 * @version $Format:%h%
 */
class phpp_StateMachine_MachineTest extends PHPUnit_Framework_TestCase
{
	public function testMachine ()
	{
		new phpp\StateMachine\Machine(Phake::mock("phpp\StateMachine\IState"));
	}
}
