<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
namespace Phur\Strategy;

class StrategicBehavior
{
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
	 */
	public function changeStrategy (IStrategy $strategy)
	{
		$this->currentStrategy = $strategy;
	}

	/**
	 * @param mixed $argument1,...
	 *
	 * @return mixed
	 */
	public function execute ($argument1 = NULL /*, ... */)
	{
		return call_user_func_array(array($this->currentStrategy, 'execute'), func_get_args());
	}
}
