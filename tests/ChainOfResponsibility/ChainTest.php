<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
class Phur_ChainOfResponsibility_ChainTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var \Phur\ChainOfResponsibility\Chain
	 */
	public $chain;

	/**
	 * @var object
	 */
	public $command;

	/**
	 * @var \Phur\ChainOfResponsibility\IProcessor
	 */
	public $processorFalse;

	/**
	 * @var \Phur\ChainOfResponsibility\IProcessor
	 */
	public $processorTrue;

	/**
	 * @var \Phur\ChainOfResponsibility\IProcessor
	 */
	public $processorAppend;

	public function setUp ()
	{
		$this->command         = new stdClass;
		$this->processorTrue   = Phake::mock('\Phur\ChainOfResponsibility\IProcessor');
		$this->processorFalse  = Phake::mock('\Phur\ChainOfResponsibility\IProcessor');
		$this->processorAppend = Phake::mock('\Phur\ChainOfResponsibility\IProcessor');

		Phake::when($this->processorTrue)->execute($this->command)->thenReturn(TRUE);
		Phake::when($this->processorFalse)->execute($this->command)->thenReturn(FALSE);
		Phake::when($this->processorAppend)->execute($this->command)->thenReturn($this->processorTrue);

		$this->chain = new \Phur\ChainOfResponsibility\Chain(array($this->processorFalse));
	}

	public function testAddProcessorFailsWithNonIProcessor ()
	{
		$this->setExpectedException('PHPUnit_Framework_Error', 'must implement interface Phur\ChainOfResponsibility\IProcessor');

		$this->chain->addProcessor(new stdClass);
	}

	public function testAddProcessorWorks ()
	{
		$result = $this->chain->addProcessor($this->processorTrue);

		$this->assertSame(2, $result);
	}

	public function testExecuteFailsWithZeroProcessors ()
	{
		$this->setExpectedException('\Phur\ChainOfResponsibility\Exception', 'Cannot execute a chain with zero processors!');

		$emptyChain = new \Phur\ChainOfResponsibility\Chain;
		$emptyChain->execute($this->command);
	}

	public function testExecuteWorksButNothingProcessed ()
	{
		$result = $this->chain->execute($this->command);

		Phake::verify($this->processorFalse)->execute($this->command);

		$this->assertSame(FALSE, $result);
	}

	public function testExecuteWorksAndChainAppendedAndProcessed ()
	{
		$this->chain->addProcessor($this->processorAppend);
		$this->chain->addProcessor($this->processorFalse);

		$result = $this->chain->execute($this->command);

		Phake::verify($this->processorFalse, Phake::times(2))->execute($this->command);
		Phake::verify($this->processorAppend)->execute($this->command);
		Phake::verify($this->processorTrue)->execute($this->command);

		$this->assertSame(TRUE, $result);
	}
}
