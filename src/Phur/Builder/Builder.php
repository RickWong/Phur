<?php

namespace Phur\Builder;

class Builder
{
    /**
     * @var \ReflectionClass
     */
    protected $productClass;
    /**
     * @var array
     */
    protected $dependencies;
    /**
     * @var array
     */
    protected $configuration;

    /**
     * @param string $productClassname
     * @param array $dependencies
     * @param array $configuration
     */
    public function __construct($productClassname, array $dependencies = array(), array $configuration = array())
    {
        $this->productClass = new \ReflectionClass($productClassname);
        $this->dependencies = $dependencies;
        $this->configuration = $configuration;
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return $this->dependencies;
    }

    /**
     * @return array
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @param string $method
     * @param array $arguments
     * @return $this
     * @throws \Phur\Builder\Exception
     */
    public function __call($method, array $arguments)
    {
        if (!$this->productClass->hasMethod($method) &&
            !$this->productClass->hasMethod('__call')
        ) {
            throw new Exception($this->productClass->getName() . "::$method() is not callable!");
        }

        $this->configuration[] = array($method, $arguments);

        return $this;
    }

    /**
     * @param string $property
     * @param mixed $value
     * @throws \Phur\Builder\Exception
     * @return $this
     */
    public function set($property, $value)
    {
        if (!is_string($property) || ctype_digit($property[0])) {
            throw new Exception('Property name cannot be numeric.');
        }

        $this->configuration[$property] = $value;

        return $this;
    }

    /**
     * @return object
     */
    public function create()
    {
        if ($this->dependencies) {
            $product = $this->productClass->newInstanceArgs($this->dependencies);
        } else {
            $product = $this->productClass->newInstance();
        }

        foreach ($this->configuration as $key => $config) {
            if (is_numeric($key)) {
                $config = (array)$config;
                $method = array_shift($config);
                $arguments = (array)array_pop($config);
                call_user_func_array(array($product, $method), $arguments);
            } else {
                $product->$key = $config;
            }
        }

        return $product;
    }
}