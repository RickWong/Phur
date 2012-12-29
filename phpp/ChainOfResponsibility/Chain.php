<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
namespace phpp\ChainOfResponsibility;

class Chain
{
	/**
	 * @var IHandler[]
	 */
	private $handlers;

	/**
	 * @parma IHandler[] $handler1, ...
	 */
	public function __construct (array $handlers = array())
	{
		$this->handlers = array();

		foreach ($handlers as $handler)
		{
			$this->addCommand($handler);
		}
	}

	/**
	 * @param IHandler $handler
	 */
	public function addCommand (IHandler $handler)
	{
		$this->handlers[] = $handler;
	}

	/**
	 * @internal param mixed $argument1, ...
	 *
	 * @return mixed
	 *
	 * @throws Exception
	 */
	public function handleCommand ()
	{
		foreach ($this->handlers as $handler)
		{
			$result = call_user_func_array(array($handler, "handleCommand"), func_get_args());

			if ($result)
			{
				return $result;
			}
		}

		throw new Exception("Chain failed to handle command!");
	}
}
