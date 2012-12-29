<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
namespace phpp\ChainOfResponsibility;

interface ICommandHandler
{
	/**
	 * @internal param mixed $argument1, ...
	 *
	 * @return mixed
	 */
	public function execute ();
}
