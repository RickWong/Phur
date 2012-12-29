<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
namespace phpp\ChainOfResponsibility;

interface IProcessor
{
	/**
	 * @param ICommand $command
	 *
	 * @return bool|IProcessor  Return FALSE to terminate the chain. If an IProcessor is returned,
	 *                          then it will be added to the end of the chain.
	 */
	public function execute (ICommand $command);
}
