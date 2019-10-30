<?php

namespace Phur\ChainOfResponsibility;

interface IProcessor
{
	/**
	 * @param mixed $command
	 *
	 * @return bool|IProcessor Returns TRUE to end chain instantly. Returns a new IProcessor to add it to the end of the chain.
	 */
	public function execute ($command);
}
