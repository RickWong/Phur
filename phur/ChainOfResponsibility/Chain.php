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
	protected $moreSpecificInterface = '\Phur\ChainOfResponsibility\IProcessor';

	/**
	 * @var IProcessor[]
	 */
	protected $processors = array();

	/**
	 * @param IProcessor $processor1,...
	 */
	public function __construct (IProcessor $processor1 = NULL /*, ...*/)
	{
		foreach (func_get_args() as $processor)
		{
			$this->addProcessor($processor);
		}
	}

	/**
	 * @param IProcessor $processor
	 *
	 * @return int
	 *
	 * @throws \Phur\ChainOfResponsibility\Exception
	 */
	public function addProcessor (IProcessor $processor)
	{
		if (!$this->isValidProcessor($processor))
		{
			throw new Exception(get_class($processor)." must implement interface $this->moreSpecificInterface!");
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

		return $this->executeProcessors($command, $this->processors);
	}

	/**
	 * @param mixed    $command
	 * @param IProcessor[] $processors
	 *
	 * @return bool
	 */
	protected function executeProcessors ($command, array $processors)
	{
		$appendedProcessors = array();

		foreach ($processors as $processor)
		{
			$result = $processor->execute($command);

			if ($result === TRUE)
			{
				return TRUE;
			}
			elseif ($this->isValidProcessor($result))
			{
				$appendedProcessors[] = $result;
			}
		}

		if (count($appendedProcessors))
		{
			return $this->executeProcessors($command, $appendedProcessors);
		}

		return FALSE;
	}

	/**
	 * @param object $object
	 *
	 * @return bool
	 */
	public function isValidProcessor ($object)
	{
		return is_object($object) && $object instanceof $this->moreSpecificInterface;
	}
}
