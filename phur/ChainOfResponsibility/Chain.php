<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
namespace Phur\ChainOfResponsibility;

class Chain
{
	/**
	 * @var IProcessor[]
	 */
	private $processors = array();

	/**
	 * @param IProcessor[] $processors
	 */
	public function __construct (array $processors = array())
	{
		foreach ($processors as $processor)
		{
			$this->addProcessor($processor);
		}
	}

	/**
	 * @param IProcessor $processor
	 *
	 * @return int
	 */
	public function addProcessor (IProcessor $processor)
	{
		$this->processors[] = $processor;

		return count($this->processors);
	}

	/**
	 * @param ICommand $command
	 *
	 * @return bool
	 *
	 * @throws \Phur\ChainOfResponsibility\Exception
	 */
	public function execute (ICommand $command)
	{
		if (count($this->processors) === 0)
		{
			throw new Exception('Cannot execute a chain with zero processors!');
		}

		foreach ($this->processors as $processor)
		{
			$result = $processor->execute($command);

			if ($result === TRUE)
			{
				return TRUE;
			}
			elseif ($result instanceof IProcessor)
			{
				$this->addProcessor($result);
			}
		}

		return FALSE;
	}
}
