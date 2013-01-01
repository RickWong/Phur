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
	protected $processors = array();

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
	 * @param mixed $command
	 *
	 * @return bool
	 *
	 * @throws \Phur\ChainOfResponsibility\Exception
	 */
	public function execute ($command)
	{
		if (count($this->processors) === 0)
		{
			throw new Exception('Cannot execute a chain with zero processors!');
		}

		return $this->_executeProcessors($command, $this->processors);
	}

	/**
	 * @param mixed        $command
	 * @param IProcessor[] $processors
	 *
	 * @return bool
	 */
	protected function _executeProcessors ($command, array $processors)
	{
		$appendedProcessors = array();

		foreach ($processors as $processor)
		{
			$result = $processor->execute($command);

			if ($result === TRUE)
			{
				return TRUE;
			}
			elseif (is_object($result) && class_implements($result, '\Phur\ChainOfResponsibility\IProcessor'))
			{
				$appendedProcessors[] = $result;
			}
		}

		if (count($appendedProcessors))
		{
			return $this->_executeProcessors($command, $appendedProcessors);
		}

		return FALSE;
	}
}
