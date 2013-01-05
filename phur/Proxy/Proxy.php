<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
namespace Phur\Proxy;

class Proxy
{
	/**
	 * @var object
	 */
	protected $target;

	/**
	 * @param object $target
	 */
	public function __construct ($target)
	{
		if (!is_object($target))
		{
			throw new Exception('Proxy target must be an object!');
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
	 * @return mixed
	 *
	 * @throws \Phur\Proxy\Exception
	 */
	public function __invoke ()
	{
		if (!is_callable($this->target))
		{
			throw new Exception(get_class($this->target).' is not callable!');
		}

		return call_user_func_array($this->target, func_get_args());
	}

	/**
	 * @return string
	 *
	 * @throws \Phur\Proxy\Exception
	 */
	public function __toString ()
	{
		if (!method_exists($this->target, '__toString'))
		{
			return get_class($this->target).'['.spl_object_hash($this->target).']';
		}

		return (string) $this->target;
	}

	/**
	 * Sleep method
	 *
	 * @return array
	 */
	public function __sleep ()
	{
		$this->target = serialize($this->target);

		return array('target');
	}

	/**
	 * Wakeup method
	 */
	public function __wakeup ()
	{
		$this->target = unserialize($this->target);
	}

	/**
	 * Clone operator
	 */
	public function __clone ()
	{
		$this->target = clone $this->target;
	}
}
