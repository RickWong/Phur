<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
namespace Phur\Strategy;

interface IStrategy
{
    /**
     * @param mixed $context
     *
     * @return mixed
     */
	public function execute ($context);
}
