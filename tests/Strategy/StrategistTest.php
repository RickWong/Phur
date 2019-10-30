<?php

class Phur_Strategy_StrategistTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var \Phur\Strategy\Strategist|Revenue_Strategist
	 */
	public $strategist;

	public function setUp ()
	{
		$this->strategist = new Revenue_Strategist(new Profit_Strategy);
	}

	public function testChangeStrategyFailsIfClassDoesNotImplementInterface ()
	{
		$this->setExpectedException('\Phur\Strategy\Exception', 'must implement interface IRevenue!');

        $this->strategist->changeStrategy(Phake::mock('\Phur\Strategy\IStrategy'));
	}

	public function testExecuteStrategyWorks ()
	{
		$result = $this->strategist->predictProfit(123);

		$this->assertSame('Profit: $123', $result);
	}

	public function testChangingStrategyAndExecutingWorks ()
	{
        $this->strategist->changeStrategy(new DoubleProfit_Strategy());

        $result = $this->strategist->predictProfit(123);

        $this->assertSame('Profit: $246', $result);
	}
}

/**
 * @method string predictProfit
 */
class Revenue_Strategist extends \Phur\Strategy\Strategist
{
    protected $strategyInterface = 'IRevenue';
}

interface IRevenue extends \Phur\Strategy\IStrategy
{
}

class Profit_Strategy implements IRevenue
{
    public function predictProfit ($context)
    {
        return "Profit: $$context";
    }
}

class DoubleProfit_Strategy implements IRevenue
{
    public function predictProfit ($context)
    {
        $context *= 2;
        return "Profit: $$context";
    }
}