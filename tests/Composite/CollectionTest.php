<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 */
class Phur_Composite_CompositeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ArrayObject|\Phur\Composite\Collection
     */
    public $collection;

    public function setUp()
    {
        $this->collection = new \Phur\Composite\Collection;
    }

    public function testArrayObjectUsage()
    {
        $data = array('prop' => 'value');

        $this->collection->exchangeArray(array((object)$data));

        $this->assertCount(1, $this->collection);
        $this->assertEquals((object)$data, $this->collection[0]);

        $this->collection[] = (object)$data;

        $this->assertCount(2, $this->collection);
        $this->assertEquals((object)$data, $this->collection[1]);

        foreach ($this->collection as $component) {
            $this->assertEquals((object)$data, $component);
        }
    }

    public function testMagicGetThrowsException()
    {
        $this->setExpectedException('\Phur\Composite\Exception', 'Trying to get $prop property from non-object at 0');

        $this->collection[] = 0x123;
        $this->collection->prop;
    }

    public function testMagicGetReturnsArray()
    {
        $this->collection[] = (object)array('prop' => 'value1');
        $this->collection[] = (object)array('prop' => 'value2');

        $this->assertSame(array('value1', 'value2'), $this->collection->prop);
    }

    public function testMagicSetThrowsException()
    {
        $this->setExpectedException('\Phur\Composite\Exception', 'Trying to set $prop property on non-object at 0');

        $this->collection[] = 0x123;
        $this->collection->prop = 2;
    }

    public function testMagicSetWorks()
    {
        $this->collection[] = (object)array('prop' => 'value1');
        $this->collection[] = (object)array('prop' => 'value2');

        $this->collection->prop = 'value';

        $this->assertSame(array('value', 'value'), $this->collection->prop);
    }

    public function testMagicIssetWorks()
    {
        $this->assertFalse(isset($this->collection->prop));

        $this->collection[] = (object)array('prop' => 'value1');

        $this->assertTrue(isset($this->collection->prop));
        $this->assertFalse(isset($this->collection->missing));
    }

    public function testMagicUnsetWorks()
    {
        $this->collection[] = (object)array('prop' => 'value1');

        $this->assertTrue(isset($this->collection->prop));

        unset($this->collection->prop);

        $this->assertFalse(isset($this->collection->prop));
    }

    public function testDistinctWorks()
    {
        $this->collection[] = (object)array('prop' => 'value');
        $this->collection[] = (object)array('prop' => 'value');

        $this->assertSame(array('value'), $this->collection->distinct('prop'));
    }

    public function testSumReturnsZero()
    {
        $this->assertSame(0, $this->collection->sum('missing'));
    }

    public function testSumWorks()
    {
        $this->collection[] = (object)array('prop' => 1);
        $this->collection[] = (object)array('prop' => 2);

        $this->assertSame(3, $this->collection->sum('prop'));
    }

    public function testAverageReturnsZero()
    {
        $this->assertSame(0, $this->collection->average('missing'));
    }

    public function testAverageWorks()
    {
        $this->collection[] = (object)array('prop' => 1);
        $this->collection[] = (object)array('prop' => 2);

        $this->assertSame(1.5, $this->collection->average('prop'));
    }

    public function testSearchRetursNull()
    {
        $this->collection[] = (object)array('prop' => 'value1');

        $this->assertNull($this->collection->search('prop', 'value2'));
    }

    public function testSearchWorks()
    {
        $this->collection[] = (object)array('prop' => 'value1');
        $this->collection[] = (object)array('prop' => 'value2');

        $this->assertSame(1, $this->collection->search('prop', 'value2'));
    }

    public function testMagicCallThrowsException()
    {
        $this->setExpectedException('\Phur\Composite\Exception', 'stdClass::undefined() is not callable');

        $this->collection[] = (object)array('prop' => 'value1');

        $this->collection->undefined();
    }

    public function testMagicCallReturnsArray()
    {
        $this->collection[] = new DOMDocument;
        $this->collection[] = new DOMDocument;

        $result = $this->collection->loadHtml('<html>');

        $this->assertSame(array(true, true), $result);

        $result = $this->collection->saveHTML();

        $this->assertCount(2, $result);
        $this->assertContains('<html></html>', $result[0]);
        $this->assertContains('<html></html>', $result[1]);
    }

    public function testMapReturnsArray()
    {
        $this->collection[] = 1;
        $this->collection[] = 2;

        $result = $this->collection->map(
            function ($value, $index) {
                return "[$index]$value";
            }
        );

        $this->assertSame(array('[0]1', '[1]2'), $result);
    }
}
