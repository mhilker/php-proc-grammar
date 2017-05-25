<?php

namespace PhpProcGrammar\Production;

use PhpProcGrammar\Common\Condition;
use PhpProcGrammar\Common\Factor;
use PhpProcGrammar\Common\Weight;
use PhpProcGrammar\Context;
use PhpProcGrammar\Grammar;
use PhpProcGrammar\Rule;

abstract class Production
{
    /**
     * @var Rule
     */
    private $rule;

    /**
     * @var Condition
     */
    private $condition;

    /**
     * @var Weight
     */
    private $weight;

    /**
     * @param Rule $rule
     */
    public function __construct(Rule $rule)
    {
        $this->rule = $rule;

        $this->condition = new Condition(true);
        $this->weight = new Weight(1.0);
    }

    /**
     * Delegate for Rule
     *
     * @param array $ruleNames
     * @return Production
     */
    public function produces(...$ruleNames): Production
    {
        return $this->rule->produces(...$ruleNames);
    }

    /**
     * Delegate for Rule
     *
     * @param array ...$ruleNames
     * @return MultiProduction
     */
    public function producesOneOf(...$ruleNames): MultiProduction
    {
        return $this->rule->producesOneOf($ruleNames);
    }

    /**
     * @param int|callable $min
     * @param int|callable $max
     * @return Production
     */
    public function firesMultipleTimes($min, $max): Production
    {
        $this->rule->firesMultipleTimes($this, new Factor($min), new Factor($max));
        return $this;
    }

    /**
     * @param bool|callable $condition
     * @return Production
     */
    public function condition($condition)
    {
        $this->condition = new Condition($condition);
        return $this;
    }

    /**
     * @param float|callable $weight
     * @return Production
     */
    public function weight($weight)
    {
        $this->weight = new Weight($weight);
        return $this;
    }

    /**
     * @internal
     * @param Context $context
     * @return float
     */
    public function calculateWeight(Context $context): float
    {
        if ($this->condition->value($context) === false) {
            return 0.0;
        }

        return $this->weight->value($context);
    }

    /**
     * @internal
     * @return Grammar
     */
    public function getGrammar(): Grammar
    {
        return $this->rule->getGrammar();
    }

    /**
     * @param array $ruleNames
     * @return Production
     */
    abstract public function then(...$ruleNames);

    /**
     * @param Context $context
     * @return void
     */
    abstract public function fire(Context $context);
}
