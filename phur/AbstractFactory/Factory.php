<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
namespace Phur\AbstractFactory;

class Factory
{
	/**
	 * @var string
	 */
	protected $productInterface = '\Phur\AbstractFactory\IProduct';

	/**
	 * @var array
	 */
	protected $defaultConstructorArgs = array();

	/**
	 * @param mixed $defaultConstructorArg1,...
	 */
	public function __construct ($defaultConstructorArg1 = NULL /*, ...*/)
	{
		$this->defaultConstructorArgs = func_get_args();
	}

	/**
	 * @param string $productClassName
	 * @param mixed  $constructorArg1,...
	 *
	 * @return object
	 *
	 * @throws \Phur\AbstractFactory\Exception
	 */
	public function create ($productClassName, $constructorArg1 = NULL /*, ...*/)
	{
		if (!is_subclass_of($productClassName, $this->productInterface))
		{
			throw new Exception("$productClassName must implement interface $this->productInterface!");
		}

		$constructorArgs = array_slice(func_get_args(), 1);

		return $this->_newInstance($productClassName, array_merge($this->defaultConstructorArgs, $constructorArgs));
	}

	/**
	 * @param string $className
	 * @param array  $constructorArgs
	 *
	 * @return object
	 */
	protected function _newInstance ($className, array $constructorArgs)
	{
		$refClass = new \ReflectionClass($className);
		return $refClass->newInstanceArgs($constructorArgs);
	}
}
