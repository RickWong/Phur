<?php
namespace Phur\Validation;


interface IRule
{
    /**
     * Matches data against this rule (or rule set).
     *
     * @param mixed $data Data to match against this rule (or rule set).
     * @return bool
     */
    public function matches($data);
}
