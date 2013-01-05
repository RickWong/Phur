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
	 * @param object $target
	 *
	 * @throws \Phur\Proxy\Exception
	 */
	public function __construct ($target)
	{
		if (!is_object($target))
		{
			throw new Exception('Target of \Phur\Proxy\ObjectProxy must be an object!');
		}

		$this->target = $target;
	}

	/**
	 * @param string $property
	 *
	 * @return mixed
	 */
	public function __get ($property)
	{
		if ($property == '__self')
		{
			return $this->target;
		}

		return $this->target->$property;
	}

	/**
	 * @param string $property
	 * @param mixed $value
	 */
	public function __set ($property, $value)
	{
		$this->target->$property = $value;
	}

	/**
	 * @param string $property
	 *
	 * @return bool
	 */
	public function __isset ($property)
	{
		return isset($this->target->$property);
	}

	/**
	 * @param $property
	 */
	public function __unset ($property)
	{
		unset($this->target->$property);
	}

	/**
	 * @param string $method
	 * @param array $arguments
	 *
	 * @return mixed
	 *
	 * @throws \Phur\Proxy\Exception
	 */
	public function __call ($method, array $arguments = array())
	{
		if (!method_exists($this->target, $method) && !method_exists($this->target, '__call'))
		{
			throw new Exception(get_class($this->target)."::$method() is not callable!");
		}

		return call_user_func_array(array($this->target, $method), $arguments);
	}

	/**
	 * @param array $arguments
	 *
	 * @return mixed
	 *
	 * @throws \Phur\Proxy\Exception
	 */
	public function __invoke (array $arguments = array())
	{
		if (is_callable($this->target))
		{
			throw new Exception(get_class($this->target).'::__invoke() is not callable!');
		}

		return call_user_func_array($this->target, $arguments);
	}

	/**
	 * @return string
	 */
	public function __toString ()
	{
		return (string) $this->target;
	}

	/**
	 * Sleep method
	 *
	 * @return mixed
	 */
	public function __sleep ()
	{
		return unserialize(serialize($this->target));
	}

	/**
	 * Wake method
	 */
	public function __wake ()
	{
		if (!method_exists($this->target, __FUNCTION__))
		{
			return;
		}

		$this->target->__wake();
	}

	/**
	 * Clone operator
	 */
	public function __clone ()
	{
		$this->target = clone $this->target;
	}
}
