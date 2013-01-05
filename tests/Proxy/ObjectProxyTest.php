<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
class Phur_Proxy_ObjectProxyTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var \Phur\Proxy\ObjectProxy
	 */
	public $object;

	public function setUp ()
	{
		$this->object = Phake::mock('\Phur\Proxy\ObjectProxy');
	}

	public function test1 ()
	{

	}
}
