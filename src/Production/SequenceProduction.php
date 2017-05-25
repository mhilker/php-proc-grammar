<?php

namespace PhpProcGrammar\Production;

use PhpProcGrammar\Context;
use PhpProcGrammar\Production\Util\Step;
use PhpProcGrammar\Rule;

class SequenceProduction extends Production
{
    /**
     * @var Step[]
     */
    private $steps = [];

    /**
     * @param Rule $rule
     * @param array $ruleNames
     */
    public function __construct(Rule $rule, array $ruleNames)
    {
        parent::__construct($rule);
        $this->then(...$ruleNames);
    }

    /**
     * @param array $ruleNames
     * @return Production
     */
    public function then(...$ruleNames)
    {
        foreach ($ruleNames as $ruleName) {
            $this->steps[] = new Step($this, $ruleName);
        }

        return $this;
    }

    /**
     * @param Context $context
     * @return void
     */
    public function fire(Context $context)
    {
        foreach ($this->steps as $step) {
            $step->fire($context);
        }
    }
}
