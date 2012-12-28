<?php
namespace phpp\Factory;

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
		$this->productInterface       = $productInterface ?: "\phpp\Factory\IProduct";
		$this->productConstructorArgs = $productConstructorArgs;
	}

	/**
	 * @param string $productClassName
	 * @param array $extraConstructorArgs (Optional)
	 * 
	 * @return IProduct
	 */
	public function produce ($productClassName, array $extraConstructorArgs = array())
	{
		if (!$productClassName instanceof $this->productInterface)
		{
			return NULL;
		}

		$allConstructorAgs = array_merge($this->productConstructorArgs, $extraConstructorArgs);

		return call_user_func_array("$productClassName::__construct", $allConstructorArgs);
	}
}
