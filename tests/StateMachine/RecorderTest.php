<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 * @package phpp
 * @version $Id$
 */
class phpp_StateMachine_RecorderTest extends\PHPUnit_Framework_TestCase
{
	public function testRecorder ()
	{
		Phake::mock("phpp\StateMachine\IState");
	}
}