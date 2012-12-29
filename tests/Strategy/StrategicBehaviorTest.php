<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
class Phur_Strategy_StrategicBehaviorTest extends PHPUnit_Framework_TestCase
{
	public function testConstructor ()
	{
		new \Phur\Strategy\StrategicBehavior(Phake::mock('\Phur\Strategy\IStrategy'));
	}
}
