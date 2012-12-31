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
	private $productInterface;

	/**
	 * @var array
	 */
	private $defaultConstructorArgs;

	/**
	 * @param string $productInterface (Optional)
	 * @param array $defaultConstructorArgs (Optional)
	 */
	public function __construct ($productInterface = NULL, array $defaultConstructorArgs = array())
	{
		$this->productInterface       = $productInterface ?: '\Phur\Factory\IProduct';
		$this->defaultConstructorArgs = $defaultConstructorArgs;
	}

	/**
	 * @param string $productClassName
	 * @param array  $constructorArgs (Optional)
	 * @param bool   $appendToDefaultArgs (Optional)
	 *
	 * @return object
	 *
	 * @throws \Phur\Factory\Exception
	 */
	public function create ($productClassName, array $constructorArgs = array(), $appendToDefaultArgs = TRUE)
	{
		if (!class_implements($productClassName, $this->productInterface))
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
	protected function _newInstance ($className, $constructorArgs)
	{
		$refClass = new \ReflectionClass($className);
		return $refClass->newInstanceArgs($constructorArgs);
	}
}
