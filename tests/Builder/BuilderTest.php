<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
class Phur_Builder_BuilderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Phur\Builder\Builder
     */
    public $builder;

    public function setUp()
    {
        $this->builder = new \Phur\Builder\Builder('DOMDocument');
    }

    public function testConstructorWorks()
    {
        $builder = new \Phur\Builder\Builder('DOMDocument', array(123), array('saveHTML'));

        $this->assertSame(array(123), $builder->getDependencies());

        $this->assertSame(array('saveHTML'), $builder->getConfiguration());
    }

    public function testMagicCallThrowsException()
    {
        $this->setExpectedException('\Phur\Builder\Exception', 'DOMDocument::undefined() is not callable!');

        $this->builder->undefined('something');
    }

    public function testMagicCallWorks()
    {
        $result = $this->builder->saveHTML('something');

        $this->assertSame($this->builder, $result);

        $this->assertSame(
            array(
                array('saveHTML', array('something'))
            ),
            $this->builder->getConfiguration()
        );
    }

    public function testSetThrowsException()
    {
        $this->setExpectedException('\Phur\Builder\Exception', 'Property name cannot be numeric');

        $this->builder->set('1property', 'value');
    }

    public function testSetWorks()
    {
        $result = $this->builder->set('property', 'value');

        $this->assertSame($this->builder, $result);

        $this->assertSame(
            array(
                'property' => 'value'
            ),
            $this->builder->getConfiguration()
        );
    }

    public function testCreateWorks()
    {
        $result = $this->builder->create();

        $this->assertInstanceOf('DOMDocument', $result);
    }


    public function testCreateWorksWithDependencies()
    {
        $builder = new \Phur\Builder\Builder('DOMDocument', (array)'1.0');
        $result = $builder->create();

        $this->assertInstanceOf('DOMDocument', $result);
        $this->assertSame('1.0', $result->version);
    }

    public function testCreateWorksWithConfiguration()
    {
        $builder = new \Phur\Builder\Builder('DOMDocument', array(), array(
            array('loadHTML', array('<font>')),
            'version' => '1.1',
        ));
        $result = $builder->create();

        $this->assertInstanceOf('DOMDocument', $result);
        $this->assertContains('<font></font>', $result->saveHTML());
        $this->assertSame('1.1', $result->version);
    }
}
