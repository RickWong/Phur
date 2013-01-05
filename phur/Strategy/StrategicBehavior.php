<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
namespace Phur\Strategy;

class StrategicBehavior
{
	/**
	 * @var string
	 */
	protected $moreSpecificInterface = '\Phur\Strategy\IStrategy';

	/**
	 * @var IStrategy
	 */
	private $currentStrategy;

	/**
	 * @param IStrategy $defaultStrategy
	 */
	public function __construct (IStrategy $defaultStrategy)
	{
		$this->changeStrategy($defaultStrategy);
	}

	/**
	 * @param IStrategy $strategy
	 *
	 * @throws \Phur\Strategy\Exception
	 */
	public function changeStrategy (IStrategy $strategy)
	{
		if (!$this->isValidStrategy($strategy))
		{
			throw new Exception(get_class($strategy)." must implement interface $this->moreSpecificInterface!");
		}

		$this->currentStrategy = $strategy;
	}

	/**
	 * @param mixed $argument1,...
	 *
	 * @return mixed
	 */
	public function execute ($argument1 = NULL /*, ...*/)
	{
		return call_user_func_array(array($this->currentStrategy, 'execute'), func_get_args());
	}

	/**
	 * @param IStrategy $strategy
	 */
	public function isValidStrategy (IStrategy $strategy)
	{
		return $strategy instanceof $this->moreSpecificInterface;
	}
}
