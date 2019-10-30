<?php
namespace Phur\Validation;


class RuleSet implements IRule
{
    /**
     * A rule interface that the rule set mandates.
     * Change this to a more specific interface if necessary.
     * Extending from \Phur\Validation\IRule is required though.
     *
     * @var string
     */
    protected $ruleInterface = '\\Phur\\Validation\\IRule';

    /**
     * Set of validation rules. A rule can be one of the following:
     * - a single rule for a field;
     * - a rule array of subrules for a field;
     * - a new rule set for a object field.
     *
     * @var IRule[]
     */
    protected $rules;

    /**
     * Constructor.
     *
     * @param IRule[] $validationRules
     */
    public function __construct(array $validationRules)
    {
        $this->rules = array();

        foreach ($validationRules as $field => $rule) {
            $this->addRule($field, $rule);
        }
    }

    /**
     * Add a rule to this validator.
     *
     * @param string        $field        Field name that also appears in the matched data.
     * @param IRule|IRule[] $rule         Validation rule or rules that apply to the field.
     * @throws \Phur\Validation\Exception
     */
    public function addRule($field, $rule)
    {
        if (!$this->isValidRule($rule)) {
            throw new Exception((is_array($rule) ? 'Rule array' : get_class(
                $rule
            )) . " for $field must implement interface $this->ruleInterface!");
        }

        $this->rules[$field] = $rule;
    }

    /**
     * Validate rule class or array.
     *
     * @param IRule|IRule[] $rule
     * @return bool
     */
    public function isValidRule($rule)
    {
        if (!is_array($rule)) {
            return $rule instanceof $this->ruleInterface;
        }

        foreach ($rule as $subrule) {
            if (!$subrule instanceof $this->ruleInterface) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return array|IRule[]
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Matches an object against this rule set.
     *
     * @param object|array $object Data object/array to match against this rule set.
     * @throws \Phur\Validation\Exception
     * @return bool
     */
    public function matches($object)
    {
        if (!is_array($object) && !is_object($object)) {
            throw new Exception(get_class($this) . " expects data to be an object or associative array.");
        }

        $object = (object)$object;

        foreach ($this->rules as $field => $rule) {
            $subrules = is_array($rule) ? $rule : array($rule);

            /** @var IRule[] $subrules */
            foreach ($subrules as $subrule) {
                if (!isset($object->$field)) {
                    return false;
                }

                if (!$subrule->matches($object->$field)) {
                    return false;
                }
            }
        }

        return true;
    }
}
