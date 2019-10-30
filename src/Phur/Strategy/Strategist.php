<?php

namespace Phur\Strategy;

/**
 * The Strategy Pattern. It is used when your application needs to
 * pick one business algorithm out of many. The behavior of your
 * application will be changeable at run-time, without having
 * to write the same conditionals everywhere.
 *
 * @example (1) We can implement a Transport_Strategy that mandates the
 *              ITransporter interface like this:
 *
 *            interface ITransporter extends \Phur\Strategy\IStrategy
 *            {
 *            }
 *
 *            class Transport_Strategy extends \Phur\Strategy\Strategist
 *            {
 *                protected $strategyInterface = 'ITransporter';
 *            }
 *
 *            class Legs implements ITransporter
 *            {
 *                public function execute ($meters) { return $meters / 1.4; } // meters per second
 *            }
 *
 *            class Car implements ITransporter
 *            {
 *                public function execute ($meters) { return $meters / 30; }
 *            }
 *
 *
 * @example (2) We then use the above Transport_Strategy to move around:
 *
 *            $transport = new Transport_Strategy(new Legs);
 *            $walkingIsSlow = $transport->execute(100);
 *
 *            $transport->changeStrategy(new Car);
 *            $carsAreFast = $transport->execute(100);
 *
 */
class Strategist
{
	/**
     * A strategy interface that the strategist mandates.
     * Change this to a more specific interface if necessary.
     * Extending from \Phur\Strategy\IStrategy is required though.
     *
	 * @var string
	 */
	protected $strategyInterface = '\Phur\Strategy\IStrategy';

	/**
     * Currently selected strategy.
     *
	 * @var \Phur\Strategy\IStrategy
	 */
	private $currentStrategy;

    /**
     * Constructor must be used to set the starting strategy.
     *
     * @param \Phur\Strategy\IStrategy $startingStrategy
     */
	public function __construct (IStrategy $startingStrategy)
	{
		$this->changeStrategy($startingStrategy);
	}

    /**
     * Change current strategy to a different one.
     *
     * @param \Phur\Strategy\IStrategy $newStrategy
     *
     * @throws \Phur\Strategy\Exception
     */
	public function changeStrategy (IStrategy $newStrategy)
	{
		if (!$this->isValidStrategy($newStrategy))
		{
			throw new Exception(get_class($newStrategy)." must implement interface $this->strategyInterface!");
		}

		$this->currentStrategy = $newStrategy;
	}

    /**
     * Validate strategy class.
     *
     * @param \Phur\Strategy\IStrategy $strategy
     *
     * @return bool
     */
    public function isValidStrategy (IStrategy $strategy)
    {
        return $strategy instanceof $this->strategyInterface;
    }

	/**
     * Execute strategy with specified context.
     *
	 * @param string $action
	 * @param array $context
	 *
	 * @return mixed
	 */
	public function __call ($action, array $context)
	{
		return call_user_func_array(array($this->currentStrategy, $action), $context);
	}
}
