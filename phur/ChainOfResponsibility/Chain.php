<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
namespace Phur\ChainOfResponsibility;

class Chain
{
	/**
	 * @var string
	 */
	protected $processorInterface = '\Phur\ChainOfResponsibility\IProcessor';

	/**
	 * @var object[]
	 */
	protected $processors = array();

	/**
	 * @param object $processor1,...
	 */
	public function __construct ($processor1 = NULL /*, ...*/)
	{
		foreach (func_get_args() as $processor)
		{
			$this->addProcessor($processor);
		}
	}

	/**
	 * @param object $processor
	 *
	 * @return int
	 *
	 * @throws \Phur\ChainOfResponsibility\Exception
	 */
	public function addProcessor ($processor)
	{
		if (!$processor instanceof $this->processorInterface)
		{
			throw new Exception(get_class($processor)." must implement interface $this->processorInterface!");
		}

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
	 * @param mixed    $command
	 * @param object[] $processors
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
			elseif (is_object($result) && $result instanceof $this->processorInterface)
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
