<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
namespace Phur\ChainOfResponsibility;

interface IProcessor
{
	/**
	 * @param ICommand $command
	 *
	 * @return bool|IProcessor Returns TRUE to end chain instantly. Returns a new IProcessor to add it to the end of the chain.
	 */
	public function execute (ICommand $command);
}
