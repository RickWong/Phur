<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
namespace phpp\Strategy;

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
		if ($defaultStrategy)
		{
			$this->changeStrategy($defaultStrategy);
		}
	}

	/**
	 * @param IStrategy $strategy
	 */
	public function changeStrategy (IStrategy $strategy)
	{
		$this->currentStrategy = $strategy;
	}

	/**
	 * @return mixed
	 */
	public function execute ()
	{
		return call_user_func_array(array($this->currentStrategy, 'execute'), func_get_args());
	}
}
