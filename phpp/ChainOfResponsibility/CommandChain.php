<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
namespace phpp\ChainOfResponsibility;

class CommandChain
{
	/**
	 * @var ICommandHandler[]
	 */
	private $handlers = array();

	/**
	 * @parma ICommandHandler[] $handlers
	 */
	public function __construct (array $handlers = array())
	{
		foreach ($handlers as $handler)
		{
			$this->addHandler($handler);
		}
	}

	/**
	 * @param ICommandHandler $handler
	 */
	public function addHandler (ICommandHandler $handler)
	{
		$this->handlers[] = $handler;
	}

	/**
	 * @internal param mixed $argument1, ...
	 *
	 * @return mixed
	 *
	 * @throws \phpp\ChainOfResponsibility\Exception
	 */
	public function execute ()
	{
		foreach ($this->handlers as $handler)
		{
			$result = call_user_func_array(array($handler, 'execute'), func_get_args());

			if ($result)
			{
				return $result;
			}
		}

		throw new Exception('Chain failed to handle command!');
	}
}
