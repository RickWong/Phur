<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
namespace Phur\AbstractFactory;

/**
 * The Abstract Factory. A factory that exists only for creating product objects
 * that must implement its mandatory PHP interface. Using interfaces instead of
 * inheritance makes the factory flexible, and the products abstract by definition.
 * See example 1 and 2.
 *
 * Factories are great for Dependency Injection. Phurs Abstract Factory is able
 * to pass dependencies down to its products through constructor injection. See
 * example 3.
 *
 * @example (1) We can implement a Model_Factory that mandates the
 *              IModel interface like this:
 *
 *            interface IModel
 *            {
 *            }
 *
 *            class Model_Factory extends \Phur\AbstractFactory\Factory
 *            {
 *                protected $productInterface = 'IModel';
 *            }
 *
 *            class Blogpost implements IModel
 *            {
 *            }
 *
 * @example (2) We then use the above Model_Factory to create a Blogpost:
 *
 *            $modelFactory = new Model_Factory;
 *            $modelFactory->create('Blogpost', array($database, 123));
 *
 *            // Same as
 *            new Blogpost($database, 123);
 *
 * @example (3) Passing dependencies down to the Blogpost:
 *
 *          Product dependencies can be passed along like this:
 *
 *            $modelFactory = new Model_Factory(array($database, $dependency));
 *            $modelFactory->create('Blogpost', 123);
 *
 *            // Same as
 *            new Blogpost($database, $dependency, 123);
 *
 */
class Factory
{
	/**
     * A product interface that the factory mandates.
     *
	 * @var string
	 */
	protected $productInterface = '\Phur\AbstractFactory\IProduct';

	/**
     * Product dependencies that are passed down to products by default on instantiation.
     *
	 * @var array
	 */
	protected $productDependencies;

	/**
     * Constructor can be used to set product dependencies.
     *
	 * @param array $productDependencies
	 */
	public function __construct (array $productDependencies = array())
	{
		$this->productDependencies = $productDependencies;
	}

	/**
     * Create a new product optionally with additional dependencies.
     *
	 * @param string $productClassName
	 * @param array  $additionalDependencies
	 *
	 * @return object
	 *
	 * @throws \Phur\AbstractFactory\Exception
	 */
	public function create ($productClassName, array $additionalDependencies = array())
	{
		if (!$this->isValidProductClassName($productClassName))
		{
			throw new Exception("$productClassName must implement interface $this->productInterface!");
		}

		return $this->newInstance($productClassName, array_merge($this->productDependencies, $additionalDependencies));
	}

	/**
     * Validate product class.
     *
	 * @param string $productClassName
	 *
	 * @return bool
	 */
	public function isValidProductClassName ($productClassName)
	{
		return is_string($productClassName) && is_subclass_of($productClassName, $this->productInterface);
	}

    /**
     * Protected method to instantiate a product.
     *
     * @param string $className
     * @param array $constructorArgs
     *
     * @return object
     *
     * @throws \Phur\AbstractFactory\Exception
     */
	protected function newInstance ($className, array $constructorArgs)
	{
		$refClass = new \ReflectionClass($className);

        if (!$refClass->getConstructor() && count($constructorArgs))
        {
            throw new Exception("$className constructor does not accept dependencies!");
        }

        return $refClass->newInstanceArgs($constructorArgs);
	}
}
