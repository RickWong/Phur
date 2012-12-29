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
	private $productConstructorArgs;

	/**
	 * @param string $productInterface (Optional)
	 * @param array $productConstructorArgs (Optional)
	 */
	public function __construct ($productInterface = NULL, array $productConstructorArgs = array())
	{
		$this->productInterface       = $productInterface ?: '\Phur\Factory\IFactoryProduct';
		$this->productConstructorArgs = $productConstructorArgs;
	}

	/**
	 * @param string $productClassName
	 * @param array  $extraConstructorArgs (Optional)
	 *
	 * @return mixed
	 *
	 * @throws \Phur\Factory\Exception
	 */
	public function create ($productClassName, array $extraConstructorArgs = array())
	{
		if (!$productClassName instanceof $this->productInterface)
		{
			throw new Exception("$productClassName must implement $this->productInterface!");
		}

		$allConstructorArgs = array_merge($this->productConstructorArgs, $extraConstructorArgs);

		return call_user_func_array(array($productClassName, '__construct'), $allConstructorArgs);
	}
}
