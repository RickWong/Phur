<?php

namespace Phur\Composite;

class Collection extends \ArrayObject
{
    /**
     * @param string $property
     * @throws \Phur\Composite\Exception
     * @return array
     */
    public function __get($property)
    {
        $result = array();

        foreach ($this->getIterator() as $index => $component) {
            if (!is_object($component)) {
                throw new Exception("Trying to get $$property property from non-object at $index");
            }

            $result[$index] = $component->$property;
        }

        return $result;
    }

    /**
     * @param string $property
     * @param mixed $value
     * @throws \Phur\Composite\Exception
     */
    public function __set($property, $value)
    {
        foreach ($this->getIterator() as $index => $component) {
            if (!is_object($component)) {
                throw new Exception("Trying to set $$property property on non-object at $index");
            }

            $component->$property = $value;
        }
    }

    /**
     * @param string $property
     * @return bool
     */
    public function __isset($property)
    {
        if (!count($this)) {
            return false;
        }

        foreach ($this->getIterator() as $component) {
            if (!isset($component->$property)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $property
     */
    public function __unset($property)
    {
        foreach ($this->getIterator() as $component) {
            unset($component->$property);
        }
    }

    /**
     * @param string $property
     * @return array
     */
    public function distinct($property)
    {
        return array_unique($this->$property);
    }

    /**
     * @param string $property
     * @return array
     */
    public function sum($property)
    {
        return array_sum($this->$property);
    }

    /**
     * @param string $property
     * @return int
     */
    public function average($property)
    {
        $count = count($this);

        if (!$count) {
            return 0;
        }

        return $this->sum($property) / $count;
    }

    /**
     * @param string $property
     * @param mixed $needle
     * @param bool $strict
     * @return mixed $key
     */
    public function search($property, $needle, $strict = true)
    {
        $results = $this->$property;

        if ($index = array_search($needle, $results, $strict)) {
            return $index;
        }

        return null;
    }

    /**
     * @param $method
     * @param array $arguments
     * @throws \Phur\Composite\Exception
     * @return mixed
     */
    public function __call($method, array $arguments)
    {
        $results = array();

        foreach ($this->getIterator() as $index => $component) {
            if (!method_exists($component, $method) && !method_exists($component, '__call')) {
                throw new Exception(get_class($component) . "::$method() is not callable!");
            }

            $results[$index] = call_user_func_array(array($component, $method), $arguments);
        }

        return $results;
    }

    /**
     * @param callable $callable
     * @return array
     */
    public function map($callable)
    {
        $results = array();

        foreach ($this->getIterator() as $index => $component) {
            $results[$index] = $callable($component, $index);
        }

        return $results;
    }
}