<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
class phpp_Factory_AbstractFactoryTest extends PHPUnit_Framework_TestCase
{
	public function testConstructor ()
	{
		Phake::mock('\phpp\Factory\AbstractFactory');
	}
}
