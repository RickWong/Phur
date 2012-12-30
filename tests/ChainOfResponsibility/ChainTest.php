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
	 * @var \Phur\ChainOfResponsibility\ICommand
	 */
	public $command;

	/**
	 * @var \Phur\ChainOfResponsibility\IProcessor
	 */
	public $processor;

	public function setUp ()
	{
		$this->command   = Phake::mock('\Phur\ChainOfResponsibility\ICommand');
		$this->processor = Phake::mock('\Phur\ChainOfResponsibility\IProcessor');
		Phake::when($this->processor)->execute(Phake::anyParameters())->thenReturn(FALSE)->thenReturn(TRUE);

		$this->chain = new \Phur\ChainOfResponsibility\Chain;
	}

	public function testAddProcessorFailsWithNonIProcessor ()
	{
		$this->setExpectedException('PHPUnit_Framework_Error', 'must implement interface Phur\ChainOfResponsibility\IProcessor');

		$this->chain->addProcessor(new stdClass);
	}

	public function testAddProcessorWorks ()
	{
		$this->chain->addProcessor($this->processor);
		$result = $this->chain->addProcessor($this->processor);

		$this->assertSame(2, $result);
	}

	public function testExecuteFailsWithZeroProcessors ()
	{
		$this->setExpectedException('\Phur\ChainOfResponsibility\Exception', 'Cannot execute a chain with zero processors!');

		$this->chain->execute($this->command);
	}

	public function testExecuteWorks ()
	{
		$this->chain->addProcessor($this->processor);
		$this->chain->addProcessor($this->processor);
		$result = $this->chain->execute($this->command);

		Phake::verify($this->processor, Phake::times(2))->execute($this->command);

		$this->assertSame(TRUE, $result);
	}
}
