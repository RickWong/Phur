<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
class Phur_Strategy_StrategistTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var \Phur\Strategy\Strategist
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
		$result = $this->strategist->execute(123);

		$this->assertSame('Profit: $123', $result);
	}

	public function testChangingStrategyAndExecutingWorks ()
	{
        $this->strategist->changeStrategy(new DoubleProfit_Strategy());

        $result = $this->strategist->execute(123);

        $this->assertSame('Profit: $246', $result);
	}
}

class Revenue_Strategist extends \Phur\Strategy\Strategist
{
    protected $strategyInterface = 'IRevenue';
}

interface IRevenue extends \Phur\Strategy\IStrategy
{
}

class Profit_Strategy implements IRevenue
{
    public function execute ($context)
    {
        return "Profit: $$context";
    }
}

class DoubleProfit_Strategy implements IRevenue
{
    public function execute ($context)
    {
        $context *= 2;
        return "Profit: $$context";
    }
}