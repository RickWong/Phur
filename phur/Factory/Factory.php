<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
namespace Phur\Factory;

class Factory
{
	/**
	 * @var string
	 */
	protected $productInterface;

	/**
	 * @var array
	 */
	protected $defaultConstructorArgs;

	/**
	 * @param string $productInterface
	 * @param array $defaultConstructorArgs
	 */
	public function __construct ($productInterface = '\Phur\Factory\IProduct', array $defaultConstructorArgs = array())
	{
		$this->productInterface       = $productInterface;
		$this->defaultConstructorArgs = $defaultConstructorArgs;
	}

	/**
	 * @param string $productClassName
	 * @param array  $constructorArgs
	 * @param bool   $appendToDefaultArgs
	 *
	 * @return object
	 *
	 * @throws \Phur\Factory\Exception
	 */
	public function create ($productClassName, array $constructorArgs = array(), $appendToDefaultArgs = TRUE)
	{
		if (!is_subclass_of($productClassName, $this->productInterface))
		{
			throw new Exception("$productClassName must implement interface $this->productInterface!");
		}

		if (count($constructorArgs) === 0)
		{
			$constructorArgs = $this->defaultConstructorArgs;
		}
		elseif ($appendToDefaultArgs)
		{
			$constructorArgs = array_merge($this->defaultConstructorArgs, $constructorArgs);
		}

		return $this->_newInstance($productClassName, $constructorArgs);
	}

	/**
	 * @param string $className
	 * @param array $constructorArgs
	 *
	 * @return object
	 */
	protected function _newInstance ($className, array $constructorArgs)
	{
		$refClass = new \ReflectionClass($className);
		return $refClass->newInstanceArgs($constructorArgs);
	}
}
