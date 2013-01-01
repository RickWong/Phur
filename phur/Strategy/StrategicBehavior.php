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
	protected $strategyInterface = '\Phur\Strategy\IStrategy';

	/**
	 * @var object
	 */
	private $currentStrategy;

	/**
	 * @param object $defaultStrategy
	 */
	public function __construct ($defaultStrategy)
	{
		$this->changeStrategy($defaultStrategy);
	}

	/**
	 * @param object $strategy
	 *
	 * @throws \Phur\Strategy\Exception
	 */
	public function changeStrategy ($strategy)
	{
		if (!$strategy instanceof $this->strategyInterface)
		{
			throw new Exception(get_class($strategy)." must implement interface $this->strategyInterface!");
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
}
