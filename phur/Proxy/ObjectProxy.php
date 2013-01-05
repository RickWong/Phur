<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
namespace Phur\Proxy;

class ObjectProxy
{
	/**
	 * @var object
	 */
	protected $target;

	/**
	 * @param object|string $target
	 */
	public function __construct ($target)
	{
		if (is_string($target) && class_exists($target))
		{
			$target = new $target;
		}

		$this->target = $target;
	}

	public function __get ($property)
	{
		return $this->target->$property;
	}

	public function __set ($property, $value)
	{
		$this->target->$property = $value;
	}

	public function __isset ($property)
	{
		return isset($this->target->$property);
	}

	public function __unset ($property)
	{
		unset($this->target->$property);
	}

	public function __call ($method, array $arguments)
	{
		return call_user_func_array(array($this->target, $method), $arguments);
	}

	public function __invoke ()
	{
		$object = $this->target;

		if (is_callable($object))
		{
			return $object();
		}
	}

	public function __toString ()
	{
		return (string) $this->target;
	}
}
