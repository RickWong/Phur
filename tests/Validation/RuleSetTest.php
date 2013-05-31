<?php
/**
 * @group  validation
 * @author Rick Wong <rick@webambition.nl>
 */
class Phur_Validation_RuleSetTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Phur\Validation\RuleSet
     */
    public $ruleSet;

    public function setUp()
    {
        $this->ruleSet = new \Phur\Validation\RuleSet(array('field' => new NotEmptyRule));
    }

    public function testConstructorAddsRules()
    {
        $rules = $this->ruleSet->getRules();

        $this->assertArrayHasKey('field', $rules);
        $this->assertInstanceOf('NotEmptyRule', $rules['field']);
    }

    public function testAddRuleThrowsExceptionIfSingleRuleInvalid()
    {
        $this->setExpectedException(
            '\\Phur\\Validation\\Exception',
            "stdClass for field must implement interface \\Phur\\Validation\\IRule!"
        );

        $this->ruleSet->addRule('field', new stdClass);
    }

    public function testAddRuleThrowsExceptionIfRuleArrayInvalid()
    {
        $this->setExpectedException(
            '\\Phur\\Validation\\Exception',
            "Rule array for field must implement interface \\Phur\\Validation\\IRule!"
        );

        $this->ruleSet->addRule('field', array(new NotEmptyRule, new stdClass));
    }

    public function testMatchesThrowsExceptionIfDataNotObjectNorArray()
    {
        $this->setExpectedException(
            '\\Phur\\Validation\\Exception',
            "RuleSet expects data to be an object or associative array."
        );

        $this->ruleSet->matches('I am not an object, nor an array.');
    }

    public function testMatchesReturnsFalse()
    {
        $this->assertFalse(
            $this->ruleSet->matches(
                array(
                    'field' => ''
                )
            )
        );

        $this->assertFalse(
            $this->ruleSet->matches(
                array(
                    'undefined' => null,
                )
            )
        );
    }

    public function testMatchesReturnsTrue()
    {
        $this->assertTrue(
            $this->ruleSet->matches(
                array(
                    'field' => 'I am not empty.'
                )
            )
        );

        $this->assertTrue(
            $this->ruleSet->matches(
                array(
                    'field'     => 'I am not empty.',
                    'undefined' => null,
                )
            )
        );
    }

    public function testMatchesRulesArray()
    {
        $ruleSet = new Phur\Validation\RuleSet(
            array(
                'field' => array(new NotEmptyRule, new AllDigitsRule)
            )
        );

        $this->assertFalse($ruleSet->matches(array('field' => '')));
        $this->assertFalse($ruleSet->matches(array('field' => '1ab')));
        $this->assertTrue($ruleSet->matches(array('field' => '123')));
    }

    public function testMatchesDeeperRuleSet()
    {
        $ruleSet = new Phur\Validation\RuleSet(
            array(
                'field'  => array(new NotEmptyRule),
                'object' => new Phur\Validation\RuleSet(
                    array(
                        'field' => array(new NotEmptyRule, new AllDigitsRule)
                    )
                )
            )
        );

        $this->assertFalse($ruleSet->matches(array('field' => '123')));
        $this->assertFalse($ruleSet->matches(array('object' => array('field' => ''))));
        $this->assertFalse($ruleSet->matches(array('object' => array('field' => '123'))));
        $this->assertTrue($ruleSet->matches(array('field' => '123', 'object' => array('field' => '123'))));
    }
}

class NotEmptyRule implements Phur\Validation\IRule
{
    public function matches($data)
    {
        return !empty($data);
    }
}

class AllDigitsRule implements Phur\Validation\IRule
{
    public function matches($data)
    {
        return ctype_digit((string)$data);
    }
}