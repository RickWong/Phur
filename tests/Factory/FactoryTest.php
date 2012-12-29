<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
class phpp_Factory_FactoryTest extends PHPUnit_Framework_TestCase
{
	public function testConstructor ()
	{
		Phake::mock('\phpp\Factory\Factory');
	}
}
