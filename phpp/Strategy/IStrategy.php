<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
namespace phpp\Strategy;

interface IStrategy
{
	/**
	 * @internal param mixed $argument1, ...
	 *
	 * @return mixed
	 */
	public function execute ();
}
