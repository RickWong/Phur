<?php

class Phur_Proxy_ProxyTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var \Phur\Proxy\Proxy
	 */
	public $object;
	/**
	 * @var object
	 */
	public $target;

	public function setUp ()
	{
		$this->target = new Phur_Proxy_TestProxy;
		$this->target->name = 'Rick';
		$this->target->nil  = NULL;

		$this->object = new \Phur\Proxy\Proxy($this->target);
	}

	public function testSetTargetFailsWithNonObject ()
	{
		$this->setExpectedException('\Phur\Proxy\Exception', 'must be an object!');

		new \Phur\Proxy\Proxy('string');
	}

	public function testGet ()
	{
		$this->assertSame($this->target->name, $this->object->name);
	}

	public function testSet ()
	{
		$this->object->name = 'Wong';

		$this->assertSame('Wong', $this->target->name);
	}

	public function testIsset ()
	{
		$this->assertTrue(isset($this->object->name));
		$this->assertFalse(isset($this->object->nil));
		$this->assertFalse(isset($this->object->unknownPropertyFoo));
	}

	public function testUnset ()
	{
		$this->assertTrue(isset($this->object->name));

		unset($this->object->name);

		$this->assertFalse(isset($this->object->name));
	}

	public function testCallFailsWithUndefinedMethod ()
	{
		$this->setExpectedException('\Phur\Proxy\Exception', '::nonexistentFunc() is not callable!');

		$this->object->nonexistentFunc();
	}

	public function testCall ()
	{
		$this->assertSame('func called with 123!', $this->object->func(123));
	}

	public function testInvokeFailsWithUndefinedInvoke ()
	{
		$this->setExpectedException('\Phur\Proxy\Exception', 'is not callable!');

		$phake = new \Phur\Proxy\Proxy(new stdClass);
		$phake();
	}

	public function testInvoke ()
	{
		$invoke = $this->object;
		$this->assertSame('Invoked with 123!', $invoke(123));
	}

	public function testToStringDefaultReturnsClassName ()
	{
		$phake = new \Phur\Proxy\Proxy(Phake::mock(__CLASS__));
		$this->assertContains(__CLASS__, (string) $phake);
	}

	public function testToString ()
	{
		$this->assertContains('String', (string) $this->object);
	}

	public function testSleepAndWakeup ()
	{
		$serialized = serialize($this->object);

		$this->assertContains('"name"', $serialized);
		$this->assertContains('"Rick"', $serialized);

		$unserialized = unserialize($serialized);

		$this->assertSame('Rick', $unserialized->name);
	}

	public function testClone ()
	{
		$clone = clone $this->object;

		$this->assertNotSame($clone, $this->object);

		$reflection = new ReflectionProperty($clone, 'target');
		$reflection->setAccessible(TRUE);

		$this->assertEquals($reflection->getValue($clone), $this->target);
	}
}

class Phur_Proxy_TestProxy
{
	public function func ($arg)
	{
		return "func called with $arg!";
	}

	public function __invoke ($arg)
	{
		return "Invoked with $arg!";
	}

	public function __toString ()
	{
		return 'String';
	}
}