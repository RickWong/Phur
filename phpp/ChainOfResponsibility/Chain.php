<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
namespace phpp\ChainOfResponsibility;

class Chain
{
	/**
	 * @var IProcessor[]
	 */
	private $processors = array();

	/**
	 * @parma IProcessor[] $processors
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
	 */
	public function addProcessor (IProcessor $processor)
	{
		$this->processors[] = $processor;
	}

	/**
	 * @param ICommand $command
	 */
	public function execute (ICommand $command)
	{
		if (count($this->processors) === 0)
		{
			throw new Exception("Cannot execute a chain with zero processors!");
		}

		foreach ($this->processors as $processor)
		{
			$result = $processor->execute($this, $command);

			if (is_bool($result) && $result === FALSE)
			{
				break;
			}
			elseif ($result instanceof IProcessor)
			{
				$this->addProcessor($result);
			}
		}
	}
}
