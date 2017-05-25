<?php

namespace PhpProcGrammar\Production\Util;

use PhpProcGrammar\Context;
use PhpProcGrammar\Production\Production;
use PhpProcGrammar\Rule;

class Step
{
    /**
     * @var Production
     */
    private $production;

    /**
     * @var Rule|callable
     */
    private $ruleOrCallable;

    /**
     * @param Production $production
     * @param $ruleOrCallable
     */
    public function __construct(Production $production, $ruleOrCallable)
    {
        $this->production = $production;
        $this->ruleOrCallable = $ruleOrCallable;
    }

    /**
     * @param Context $context
     * @return void
     */
    public function fire(Context $context): void
    {
        if (is_callable($this->ruleOrCallable)) {
            ($this->ruleOrCallable)($context);
        } else if ($this->production->getGrammar()->has($this->ruleOrCallable)) {
            $this->production->getGrammar()->rule($this->ruleOrCallable)->fire($context);
        } else {
            $context->setCurrent($this->ruleOrCallable);
        }
    }
}
