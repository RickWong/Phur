<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
namespace phpp\Factory;

abstract class Factory
{
	/**
	 * @var string
	 */
	protected $productInterface = '\phpp\Factory\IFactoryProduct';

	/**
	 * @var array
	 */
	private $productConstructorArgs;

	/**
	 * @param array $productConstructorArgs (Optional)
	 */
	public function __construct (array $productConstructorArgs = array())
	{
		$this->productConstructorArgs = $productConstructorArgs;
	}

	/**
	 * @param string $productClassName
	 * @param array $extraConstructorArgs (Optional)
	 * 
	 * @return mixed|IFactoryProduct
	 */
	public function create ($productClassName, array $extraConstructorArgs = array())
	{
		if (!$productClassName instanceof $this->productInterface)
		{
			return NULL;
		}

		$allConstructorArgs = array_merge($this->productConstructorArgs, $extraConstructorArgs);

		return call_user_func_array(array($productClassName, '__construct'), $allConstructorArgs);
	}
}
